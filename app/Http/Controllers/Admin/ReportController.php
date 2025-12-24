<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\GroqService;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $query = Transaction::with(['user', 'items.product'])
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Clone query for total calculation before pagination
        $totalRevenue = $query->sum('total_amount');

        $transactions = $query->latest()->get(); // Get all for report without pagination usually, or paginate if needed. Let's get all for now for accurate total in list? Or paginate and show total of *filtered* data? Use get() for reports usually creates a clearer picture.

        // Calculate total profit
        $totalProfit = 0;
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                if ($item->product && $item->product->buy_price) {
                    $profit = ($item->price - $item->product->buy_price) * $item->quantity;
                    $totalProfit += $profit;
                }
            }
        }

        // Calculate margin percentage
        $totalCost = $totalRevenue - $totalProfit;
        $marginPercentage = $totalCost > 0 ? ($totalProfit / $totalCost) * 100 : 0;

        return view('admin.reports.index', compact('transactions', 'totalRevenue', 'totalProfit', 'marginPercentage'));
    }


    public function print(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $query = Transaction::with(['user', 'items.product'])
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRevenue = $query->sum('total_amount');
        $transactions = $query->latest()->get();

        // Calculate total profit
        $totalProfit = 0;
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                if ($item->product && $item->product->buy_price) {
                    $profit = ($item->price - $item->product->buy_price) * $item->quantity;
                    $totalProfit += $profit;
                }
            }
        }

        // Calculate margin percentage
        $totalCost = $totalRevenue - $totalProfit;
        $marginPercentage = $totalCost > 0 ? ($totalProfit / $totalCost) * 100 : 0;

        return view('admin.reports.print', compact('transactions', 'totalRevenue', 'totalProfit', 'marginPercentage'));
    }

    public function analyze(Request $request, GroqService $groq)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // 1. Ambil Data (sama logic dengan index/print)
        $query = Transaction::with(['items.product']) // Load items & product for simpler analysis
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();

        if ($totalTransactions == 0) {
            return response()->json(['analysis' => 'Tidak ada data transaksi pada periode ini untuk dianalisa.']);
        }

        // 2. Ringkas Data (Agar muat di Context Window LLM)
        // Kita hitung produk terlaris manual di sini untuk dikirim ke AI
        $productStats = [];
        foreach ($transactions as $t) {
            foreach ($t->items as $item) {
                $name = $item->product->name ?? 'Unknown';
                if (!isset($productStats[$name])) {
                    $productStats[$name] = 0;
                }
                $productStats[$name] += $item->quantity;
            }
        }
        arsort($productStats);
        $topProducts = array_slice($productStats, 0, 5); // Ambil top 5

        $summaryData = [
            'total_pendapatan' => $totalRevenue,
            'total_transaksi' => $totalTransactions,
            'periode' => ($request->start_date ?? 'Awal') . ' s/d ' . ($request->end_date ?? 'Sekarang'),
            'top_produk' => $topProducts
        ];

        $prompt = "Berikut adalah ringkasan data penjualan toko Sembako:\n" . json_encode($summaryData, JSON_PRETTY_PRINT);
        $prompt .= "\n\nTolong berikan analisa singkat (maksimal 3 paragraf) dalam Bahasa Indonesia gaya profesional tapi mudah dimengerti. ";
        $prompt .= "Sebutkan tren penjualan, produk apa yang dominan, dan saran untuk stok (misal: perbanyak yang laku).";

        // 3. Panggil AI
        $analysis = $groq->chat($prompt, 'Kamu adalah analis bisnis senior di toko retail sembako.');

        return response()->json(['analysis' => $analysis]);
    }
}
