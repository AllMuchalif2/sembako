<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function summary()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        $total = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'count' => $count,
            'total' => $total,
            'total_formatted' => 'Rp' . number_format($total, 0, ',', '.'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan quantity-nya
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            // Jika belum ada, tambahkan sebagai item baru
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->image,
                "slug" => $product->slug,
                "stock" => $product->stock, // Tambahkan 'stock' di sini
            ];
        }

        // Selalu perbarui stok di session untuk memastikan data akurat
        $cart[$product->id]['stock'] = $product->stock;

        // Pastikan total kuantitas di keranjang tidak melebihi stok
        if ($cart[$product->id]['quantity'] > $product->stock) {
            $cart[$product->id]['quantity'] = $product->stock;
            session()->flash('warning', 'Kuantitas produk "' . $product->name . '" disesuaikan dengan stok yang tersedia.');
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return $this->summary();
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }


    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if (request()->expectsJson()) {
            return $this->summary();
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::findOrFail($id); // Ambil produk dari DB untuk cek stok terbaru

            $newQuantity = $request->quantity;

            // Pastikan kuantitas tidak melebihi stok yang tersedia
            if ($newQuantity > $product->stock) {
                $newQuantity = $product->stock;
                session()->flash('warning', 'Kuantitas produk "' . $product->name . '" dibatasi karena stok tidak mencukupi.');
            }

            $cart[$id]['quantity'] = $newQuantity;
            $cart[$id]['stock'] = $product->stock; // Perbarui stok di session jika ada perubahan

            session()->put('cart', $cart);
            session()->flash('success', 'Kuantitas produk berhasil diperbarui.');
        } else {
            session()->flash('error', 'Produk tidak ditemukan di keranjang.');
        }

        if ($request->expectsJson()) {
            $item = $cart[$id] ?? null;
            $subtotal = $item ? $item['price'] * $item['quantity'] : 0;

            return response()->json([
                'cart' => $this->summary()->getData(),
                'item_quantity' => $item['quantity'] ?? 0,
                'item_subtotal_formatted' => 'Rp' . number_format($subtotal, 0, ',', '.'),
                'warning' => session()->get('warning'),
            ]);
        }
        return redirect()->back();
    }
}
