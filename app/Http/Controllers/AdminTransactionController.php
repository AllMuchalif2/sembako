<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of the transactions for admin.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified transaction for admin.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Update the status of the specified transaction.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|string|in:pending,diproses,dikirim,selesai,dibatalkan',
        ]);

        // Optional: Add logic for status transitions (e.g., cannot go from 'selesai' to 'pending')
        // if ($transaction->status === 'selesai' && $request->status !== 'selesai') {
        //     return redirect()->back()->with('error', 'Transaksi selesai tidak dapat diubah statusnya.');
        // }

        $transaction->update(['status' => $request->status]);

        // If status changes to 'dibatalkan', restore product stock
        if ($request->status === 'dibatalkan') {
            foreach ($transaction->items as $item) {
                Product::find($item->product_id)->increment('stock', $item->quantity);
            }
            // Also update payment status if cancelled
            $transaction->update(['payment_status' => 'cancel']);
        }

        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Generate and display the invoice for a transaction.
     */
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
