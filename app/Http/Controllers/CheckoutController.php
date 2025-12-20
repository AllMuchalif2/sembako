<?php

namespace App\Http\Controllers;


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
            'payment_method' => 'required|in:midtrans,cod',
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
            'payment_method' => $request->payment_method,
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

        // Handle payment based on selected method
        if ($request->payment_method === Transaction::PAYMENT_METHOD_COD) {
            // COD: Skip Midtrans, clear cart, redirect to COD success page
            session()->forget('cart');
            session()->forget('promo');
            Log::info('Checkout process: COD order created, cart cleared, redirecting to COD success page.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);

            return redirect()->route('checkout.cod-success', ['order_id' => $transaction->order_id]);
        }

        // Midtrans: Redirect to payment page
        session()->forget('cart');
        session()->forget('promo');
        Log::info('Checkout process: Midtrans order created, cart cleared, redirecting to payment controller.', ['order_id' => $transaction->order_id, 'user_id' => Auth::id() ?? 'guest']);

        return redirect()->route('payment.pay', ['order_id' => $transaction->order_id]);
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

    public function codSuccess($order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($transaction->payment_method !== Transaction::PAYMENT_METHOD_COD) {
            return redirect()->route('transactions.index')->with('error', 'Halaman ini hanya untuk pesanan COD.');
        }

        return view('checkout.cod-success', ['order' => $transaction]);
    }


}
