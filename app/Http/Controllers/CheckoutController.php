<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Promo;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use App\Helpers\LocationHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        $promo = Session::get('promo');
        $discountAmount = $promo['discount_amount'] ?? 0;
        $finalTotal = $subtotal - $discountAmount;

        $settings = StoreSetting::getSettings();

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'finalTotal' => $finalTotal,
            'user' => $user,
            'settings' => $settings,
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
            if (!$product) {
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

        // Ambil pengaturan toko dari database
        $settings = StoreSetting::getSettings();

        // Hitung jarak
        $distance = LocationHelper::calculateDistance(
            $settings->store_latitude,
            $settings->store_longitude,
            $request->latitude,
            $request->longitude
        );

        // Validasi jarak maksimal dari pengaturan
        if ($distance > $settings->max_delivery_distance) {
            Log::warning('Checkout process: Delivery location too far.', [
                'distance' => $distance,
                'max_allowed' => $settings->max_delivery_distance,
                'user_id' => Auth::id()
            ]);
            return redirect()->route('checkout.index')->with(
                'error',
                'Lokasi pengiriman terlalu jauh! Maksimal ' . ($settings->max_delivery_distance / 1000) . ' km dari toko. Jarak Anda: ' . number_format($distance / 1000, 2) . ' km'
            );
        }

        // Hitung ongkir berdasarkan pengaturan
        $shippingCost = LocationHelper::calculateShippingCost(
            $distance,
            $settings->free_shipping_radius,
            $settings->shipping_cost
        );

        $finalTotal = $subtotal - $discountAmount + $shippingCost;


        // Buat transaksi baru di database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'order_id' => 'INV-' . time(),
            'total_amount' => $finalTotal,
            'promo_code' => $promoCode,
            'discount_amount' => $discountAmount,
            'distance_from_store' => $distance,
            'shipping_cost' => $shippingCost,
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

        // Generate Snap token
        try {
            $snapToken = $this->generateSnapToken($transaction);

            session()->forget('cart');
            session()->forget('promo');
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

            // 2. Ambil data dari request
            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status;

            // 3. Cari transaksi berdasarkan order_id
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                Log::warning('Midtrans callback: Transaction not found in DB.', ['order_id_from_request' => $orderId]);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }

            // 4. Jangan proses notifikasi yang sama berulang kali (Idempotency)
            if ($transaction->status === 'diproses' || $transaction->status === 'settlement') {
                Log::info('Midtrans callback: Transaction already marked as success.', ['order_id' => $orderId]);
                return response()->json(['message' => 'Transaction already processed.'], 200);
            }

            // 5. Handle status berdasarkan notifikasi
            if ($transactionStatus == 'settlement') {
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                ]);
                Log::info('Midtrans callback: Transaction status updated to settlement.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
                $transaction->update([
                    'payment_status' => 'settlement',
                    'status' => 'diproses',
                ]);
                Log::info('Midtrans callback: Transaction status updated to success after capture.', ['order_id' => $orderId]);
            } else if ($transactionStatus == 'pending') {
                $transaction->update([
                    'payment_status' => 'pending',
                ]);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $transaction->update([
                    'payment_status' => 'failed',
                    'status' => 'failed'
                ]);

                // Kembalikan stok produk
                foreach ($transaction->items as $item) {
                    Product::find($item->product_id)->increment('stock', $item->quantity);
                }
                Log::info('Midtrans callback: Transaction failed, stock returned.', ['order_id' => $orderId]);
            } else if ($fraudStatus == 'challenge') {
                $transaction->update([
                    'payment_status' => 'challenge',
                    'status' => 'challenge',
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

        if (!$orderId) {
            return redirect()->route('transactions.index')->with('error', 'Order ID tidak ditemukan.');
        }

        $transaction = Transaction::where('order_id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        // FORCED SUCCESS for InfinityFree (no callback support)
        // If user reaches this page with valid order_id, assume payment success
        if ($transaction->status == 'pending') {
            $transaction->update([
                'status' => 'diproses',
                'payment_status' => 'settlement'
            ]);
            Log::info('Success Page: Forced status update (InfinityFree workaround)', ['order_id' => $orderId]);
        }

        return view('checkout.success', ['order' => $transaction]);
    }

    public function pay($order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)->where('user_id', Auth::id())->firstOrFail();

        if ($transaction->status != 'pending') {
            return redirect()->route('transactions.index')->with('error', 'Transaksi ini tidak dapat dibayar lagi.');
        }

        // Generate new Snap token for retry
        try {
            $snapToken = $this->generateSnapToken($transaction);
            return view('checkout.payment', ['snapToken' => $snapToken, 'client_key' => config('midtrans.client_key'), 'order' => $transaction]);
        } catch (\Exception $e) {
            Log::error('Pay method: Failed to generate Snap token.', ['error' => $e->getMessage(), 'order_id' => $order_id]);
            return redirect()->route('transactions.index')->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Generate Midtrans Snap token for a transaction
     * 
     * @param Transaction $transaction
     * @return string Snap token
     * @throws \Exception
     */
    private function generateSnapToken(Transaction $transaction): string
    {
        // Prepare item details
        $item_details = [];
        foreach ($transaction->items as $item) {
            $item_details[] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => Str::limit($item->product_name, 50),
            ];
        }

        // Prepare Snap parameters
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

        Log::info('Generating Midtrans Snap token.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);

        $snapToken = Snap::getSnapToken($params);
        $transaction->snap_token = $snapToken;
        $transaction->save();

        return $snapToken;
    }
}
