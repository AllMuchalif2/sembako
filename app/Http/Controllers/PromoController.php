<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PromoController extends Controller
{

    
    /**
     * Menerapkan kode promo ke keranjang belanja.
     */
    public function apply(Request $request)
    {
        $request->validate(['promo_code' => 'required|string']);

        $promoCode = $request->promo_code;
        $cartTotal = $this->getCartTotal();

        $promo = Promo::where('code', $promoCode)->first();

        // Validasi 1: Cek apakah promo ada
        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Kode promo tidak ditemukan.'], 404);
        }

        // Validasi 2: Cek status dan tanggal
        if ($promo->status !== 'active' || now()->isBefore($promo->start_date) || now()->isAfter($promo->end_date)) {
            return response()->json(['success' => false, 'message' => 'Kode promo tidak valid atau sudah kedaluwarsa.'], 400);
        }

        // Validasi 3: Cek batas penggunaan global
        if ($promo->usage_limit !== null && $promo->times_used >= $promo->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Kuota penggunaan promo sudah habis.'], 400);
        }

        // Validasi 4: Cek minimal pembelian
        if ($promo->min_purchase !== null && $cartTotal < $promo->min_purchase) {
            return response()->json(['success' => false, 'message' => 'Total belanja tidak memenuhi syarat minimal.'], 400);
        }

        // Validasi 5: Cek batas penggunaan per pengguna
        if ($promo->limit_per_user) {
            $usageCount = $promo->usages()->where('user_id', Auth::id())->count();
            if ($usageCount > 0) {
                return response()->json(['success' => false, 'message' => 'Anda sudah pernah menggunakan kode promo ini.'], 400);
            }
        }

        // Jika semua validasi lolos, hitung diskon
        $discountAmount = 0;
        if ($promo->type === 'percentage') {
            $discountAmount = ($promo->value / 100) * $cartTotal;
            if ($promo->max_discount !== null && $discountAmount > $promo->max_discount) {
                $discountAmount = $promo->max_discount;
            }
        } else { // 'fixed'
            $discountAmount = $promo->value;
        }

        // Pastikan diskon tidak lebih besar dari total belanja
        $discountAmount = min($discountAmount, $cartTotal);

        // Simpan informasi promo ke session
        Session::put('promo', [
            'id' => $promo->id,
            'code' => $promo->code,
            'discount_amount' => $discountAmount,
        ]);

        $newTotal = $cartTotal - $discountAmount;

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diterapkan!',
            'discount_formatted' => '- Rp' . number_format($discountAmount, 0, ',', '.'),
            'new_total_formatted' => 'Rp' . number_format($newTotal, 0, ',', '.'),
        ]);
    }

    /**
     * Menghapus kode promo dari session.
     */
    public function remove()
    {
        Session::forget('promo');

        $cartTotal = $this->getCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil dihapus.',
            'new_total_formatted' => 'Rp' . number_format($cartTotal, 0, ',', '.'),
        ]);
    }

    /**
     * Helper untuk menghitung total keranjang.
     */
    private function getCartTotal(): float
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
