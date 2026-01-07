<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $query = Transaction::with('user');

        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
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
        $settings = StoreSetting::getSettings();
        $admin = auth()->user();

        return view('admin.transactions.invoice', compact('transaction', 'settings', 'admin'));
    }

    public function confirmCodOrder(Transaction $transaction)
    {
        // Validate that order is COD and pending
        if ($transaction->payment_method !== Transaction::PAYMENT_METHOD_COD) {
            return redirect()->back()->with('error', 'Pesanan ini bukan metode COD.');
        }

        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah dikonfirmasi.');
        }

        // Update status to diproses
        $transaction->update(['status' => 'diproses']);

        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Pesanan COD berhasil dikonfirmasi dan sedang diproses.');
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        // Only allow canceling pending or diproses orders
        if (!in_array($transaction->status, ['pending', 'diproses'])) {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status pending atau diproses yang dapat dibatalkan.');
        }

        // Validate cancellation reason
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan harus diisi.',
            'cancellation_reason.max' => 'Alasan pembatalan maksimal 500 karakter.'
        ]);

        // Restore product stock
        foreach ($transaction->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        // Append cancellation reason to notes
        $cancellationNote = "\n\n[DIBATALKAN OLEH ADMIN]\nTanggal: " . now()->locale('id')->translatedFormat('d M Y H:i') . "\nAlasan: " . $request->cancellation_reason;
        $updatedNotes = $transaction->notes ? $transaction->notes . $cancellationNote : $cancellationNote;

        // Update transaction status and notes
        $transaction->update([
            'status' => 'dibatalkan',
            'notes' => $updatedNotes
        ]);

        return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.');
    }
}
