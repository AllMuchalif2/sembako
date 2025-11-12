<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar riwayat transaksi pengguna.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail satu transaksi.
     */
    public function show(Transaction $transaction)
    {
        // Pastikan pengguna hanya bisa melihat transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load relasi items beserta produk di dalamnya
        $transaction->load('items.product');

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Menandai transaksi sebagai 'selesai' oleh pelanggan.
     */
    public function markAsCompleted(Request $request, Transaction $transaction)
    {
        // Pastikan pengguna adalah pemilik transaksi
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan status saat ini adalah 'dikirim'
        if ($transaction->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Status transaksi tidak dapat diubah.');
        }

        $transaction->update(['status' => 'selesai']);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Terima kasih telah berbelanja! Transaksi Anda telah selesai.');
    }

    /**
     * Membatalkan transaksi oleh pelanggan.
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        // Pastikan pengguna adalah pemilik transaksi
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($transaction->status !== 'pending' && $transaction->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }

        // Ubah status transaksi
        $transaction->update([
            'status' => 'dibatalkan',
            // 'payment_status' => 'cancel'
        ]);

        // Kembalikan stok produk
        foreach ($transaction->items as $item) {
            Product::find($item->product_id)->increment('stock', $item->quantity);
        }

        return redirect()->route('transactions.show', $transaction)->with('success', 'Pesanan Anda berhasil dibatalkan.');
    }
}
