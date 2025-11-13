<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        // Filter berdasarkan Order ID
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

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail satu transaksi.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Mengubah status transaksi.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|string|in:dikirim']);

        $transaction->update(['status' => $request->status]);

        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function invoice(Transaction $transaction)
    {
        // Ensure the transaction status allows printing an invoice
        if (!in_array($transaction->status, ['diproses', 'dikirim', 'selesai'])) {
            abort(403, 'Invoice can only be generated for processed, shipped, or completed transactions.');
        } 

        // Load necessary relations
        $transaction->load(['user', 'items.product']);

        return view('admin.transactions.invoice', compact('transaction'));
    }
}
