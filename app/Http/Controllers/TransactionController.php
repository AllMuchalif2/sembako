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
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id());

        // Filter berdasarkan Order ID (nama)
        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        // Filter berdasarkan status transaksi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

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

        if ($transaction->status == 'pending' || $transaction->status == 'diproses') {
            // Validate cancellation reason
            $request->validate([
                'cancellation_reason' => 'required|string|max:500'
            ], [
                'cancellation_reason.required' => 'Alasan pembatalan harus diisi.',
                'cancellation_reason.max' => 'Alasan pembatalan maksimal 500 karakter.'
            ]);

            // Append cancellation reason to notes
            $cancellationNote = "\n\n[DIBATALKAN OLEH CUSTOMER]\nTanggal: " . now()->format('d M Y H:i') . "\nAlasan: " . $request->cancellation_reason;
            $updatedNotes = $transaction->notes ? $transaction->notes . $cancellationNote : $cancellationNote;

            // Ubah status transaksi
            $transaction->update([
                'status' => 'dibatalkan',
                'notes' => $updatedNotes
            ]);

            // Kembalikan stok produk
            foreach ($transaction->items as $item) {
                Product::find($item->product_id)->increment('stock', $item->quantity);
            }

            return redirect()->route('transactions.show', $transaction)->with('success', 'Pesanan Anda berhasil dibatalkan.');
        }
        return redirect()->back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
    }
}
