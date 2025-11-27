<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }


    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');
        $settings = StoreSetting::getSettings();
        return view('admin.transactions.show', compact('transaction', 'settings'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|string|in:dikirim']);

        $transaction->update(['status' => $request->status]);

        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function invoice(Transaction $transaction)
    {
        if (!in_array($transaction->status, ['diproses', 'dikirim', 'selesai'])) {
            abort(403, 'Invoice can only be generated for processed, shipped, or completed transactions.');
        } 

        $transaction->load(['user', 'items.product']);

        return view('admin.transactions.invoice', compact('transaction'));
    }
}
