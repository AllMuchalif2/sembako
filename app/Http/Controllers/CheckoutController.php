<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Menampilkan halaman detail checkout dengan peta.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cartItems', 'total'));
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
        $totalPrice = 0;
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
            $totalPrice += $item['price'] * $item['quantity'];
            $item_details[] = [
                'id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => Str::limit($item['name'], 50),
            ];
        }

        // Buat transaksi baru di database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'order_id' => 'INV-' . time(),
            'total_amount' => $totalPrice,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes,
        ]);

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
            Log::info('Checkout process: Snap token generated, cart cleared, redirecting to payment view.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);
            
            return view('checkout.payment', ['snapToken' => $snapToken, 'order' => $transaction]);
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
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $transaction = Transaction::where('order_id', $request->order_id)->first();
                if ($transaction) {
                    $transaction->update(['payment_status' => 'success', 'status' => 'success']);
                }
            }
        }
    }

    public function success()
    {
        return view('checkout.success');
    }
}
