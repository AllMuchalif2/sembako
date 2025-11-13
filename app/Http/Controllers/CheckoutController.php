<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Menampilkan halaman detail checkout dengan peta.
     */
    public function index()
    {
        $user = Auth::user();

        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Cek jika ada promo di session
        $promo = Session::get('promo');
        $discountAmount = $promo['discount_amount'] ?? 0;
        $finalTotal = $subtotal - $discountAmount;

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'finalTotal' => $finalTotal,
            'user' => $user,
        ]);
    }

    /**
     * Memproses checkout, membuat transaksi, dan mendapatkan Snap Token.
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            Log::warning('Checkout process: Cart is empty on submission. Redirecting to cart index.', ['user_id' => Auth::id() ?? 'guest']);
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Hitung total harga dan siapkan item detail
        $subtotal = 0;
        $item_details = [];
        foreach ($cartItems as $id => $item) {
            $product = Product::find($id);
            if (!$product) { // Tambahkan pengecekan jika produk tidak ditemukan
                Log::error('Checkout process: Product not found in DB for cart item. Redirecting to cart index.', ['product_id' => $id, 'user_id' => Auth::id() ?? 'guest']);
                return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
            }
            if ($product->stock < $item['quantity']) {
                Log::warning('Checkout process: Insufficient stock for product. Redirecting to cart index.', ['product_id' => $id, 'requested_quantity' => $item['quantity'], 'available_stock' => $product->stock, 'user_id' => Auth::id() ?? 'guest']);
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $item['name'] . ' tidak mencukupi.');
            }
            $subtotal += $item['price'] * $item['quantity'];
            $item_details[] = [
                'id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => Str::limit($item['name'], 50),
            ];
        }

        // Hitung diskon dari session
        $promo = Session::get('promo');
        $discountAmount = $promo['discount_amount'] ?? 0;
        $promoCode = $promo['code'] ?? null;
        $finalTotal = $subtotal - $discountAmount;


        // Buat transaksi baru di database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'order_id' => 'INV-' . time(),
            'total_amount' => $finalTotal,
            'promo_code' => $promoCode,
            'discount_amount' => $discountAmount,
            // 'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes,
        ]);

        // Jika promo digunakan, catat penggunaannya
        if ($promo) {
            $transaction->promoUsages()->create(['user_id' => Auth::id(), 'promo_id' => $promo['id']]);
            Promo::find($promo['id'])->increment('times_used');
        }

        // Simpan item transaksi
        foreach ($cartItems as $id => $item) {
            $transaction->items()->create([
                'product_id' => $id,
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            // Kurangi stok produk
            Product::find($id)->decrement('stock', $item['quantity']);
        }

        // Siapkan parameter untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'callbacks' => [
                'finish' => route('checkout.success', ['order_id' => $transaction->order_id]),
                'error' => route('cart.index', ['status' => 'error', 'order_id' => $transaction->order_id]),
                'pending' => route('cart.index', ['status' => 'pending', 'order_id' => $transaction->order_id]),
            ],
            'item_details' => $item_details,
        ];

        try {
            Log::info('Checkout process: Attempting to get Midtrans Snap token.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);
            $snapToken = Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            session()->forget('cart');
            session()->forget('promo'); // Hapus promo setelah berhasil checkout
            Log::info('Checkout process: Snap token generated, cart cleared, redirecting to payment view.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);

            return view('checkout.payment', ['snapToken' => $snapToken, 'client_key' => config('midtrans.client_key'), 'order' => $transaction]);
        } catch (\Exception $e) {
            // Rollback: kembalikan stok dan hapus transaksi jika gagal
            foreach ($transaction->items as $item) {
                Product::find($item->product_id)->increment('stock', $item->quantity);
            }
            $transaction->delete();
            Log::error('Checkout process: Midtrans Snap token generation failed. Redirecting to cart index.', ['error' => $e->getMessage(), 'user_id' => Auth::id() ?? 'guest', 'order_id' => $transaction->order_id ?? 'N/A']);
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function markAsProcessed(Request $request, $order_id)
    {
        Log::info('FAKE SUCCESS: Memicu status "diproses" untuk order.', ['order_id' => $order_id]);

        $transaction = Transaction::where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($transaction && $transaction->status === 'pending') {
            // INI INTINYA: Langsung ubah status
            $transaction->update(['status' => 'diproses']);

            return response()->json(['success' => true, 'message' => 'Status diubah ke diproses.']);
        }

        if ($transaction) {
            return response()->json(['success' => false, 'message' => 'Status sudah diproses.'], 200);
        }

        return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
    }
    public function callback(Request $request)
    {
        Log::info('Midtrans notification received.', $request->all());

        try {
            // 1. Verifikasi Signature Key
            $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . config('midtrans.server_key'));
            if ($signatureKey != $request->signature_key) {
                Log::warning('Midtrans callback: Invalid signature.', ['order_id' => $request->order_id]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // 2. Ambil data langsung dari request Laravel yang sudah terbukti benar dari log
            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;
            // $paymentType = $request->payment_type ?? null;
            $fraudStatus = $request->fraud_status;

            // 3. Cari transaksi berdasarkan order_id yang benar
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                Log::warning('Midtrans callback: Transaction not found in DB.', ['order_id_from_request' => $orderId]);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }

            // 3. Jangan proses notifikasi yang sama berulang kali (Idempotency)
            if ($transaction->payment_status === 'settlement') {
                Log::info('Midtrans callback: Transaction already marked as success.', ['order_id' => $orderId]);
                return response()->json(['message' => 'Transaction already processed.'], 200);
            }

            // 5. Handle status berdasarkan notifikasi
            if ($transactionStatus == 'settlement') {
                // Transaksi berhasil dan dana sudah masuk.
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                    // 'payment_type' => $paymentType
                ]);
                Log::info('Midtrans callback: Transaction status updated to settlement.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
                // Khusus untuk kartu kredit, setelah 'capture' dan fraud 'accept'
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                    // 'payment_type' => $paymentType
                ]);
                Log::info('Midtrans callback: Transaction status updated to success after capture.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'pending') {
                // Transaksi menunggu pembayaran
                $transaction->update([
                    'payment_status' => 'pending',
                    // 'payment_type' => $paymentType
                ]);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                // Transaksi gagal, dibatalkan, atau kadaluarsa
                $transaction->update([
                    'payment_status' => 'failed',
                    'status' => 'failed'
                ]);

                // 3. Kembalikan stok produk karena pembayaran gagal
                foreach ($transaction->items as $item) {
                    Product::find($item->product_id)->increment('stock', $item->quantity);
                }
                Log::info('Midtrans callback: Transaction failed, stock returned.', ['order_id' => $orderId]);
            } else if ($fraudStatus == 'challenge') {
                // Transaksi ditahan karena dugaan fraud
                $transaction->update([
                    'payment_status' => 'challenge',
                    'status' => 'challenge',
                    // 'payment_type' => $paymentType
                ]);
                Log::warning('Midtrans callback: Transaction is challenged by FDS.', ['order_id' => $orderId]);
            }

            return response()->json(['message' => 'Notification handled successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage(), ['request_payload' => $request->all(), 'exception' => $e]);
            return response()->json(['message' => 'Error handling notification.'], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('checkout.success', ['order' => $transaction]);
    }
}
