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
        $query = Transaction::with('user')
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

        return view('admin.reports.index', compact('transactions', 'totalRevenue'));
    }


    public function print(Request $request)
    {
        $query = Transaction::with('user')
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRevenue = $query->sum('total_amount');
        $transactions = $query->latest()->get();

        return view('admin.reports.print', compact('transactions', 'totalRevenue'));
    }

    public function analyze(Request $request, GroqService $groq)
    {
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
