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
        $query = Transaction::with('user')->latest();

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(15)->withQueryString();

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
}
