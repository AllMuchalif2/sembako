<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        $newOrders = Transaction::where('status', 'diproses')->count();

        $todaysRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_amount');

        $lowStockProducts = Product::where('stock', '<', 10)->count();

        $lowStockProductsList = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        $totalRevenue = Transaction::where('status', 'selesai')->sum('total_amount');

        // Data untuk grafik pendapatan 7 hari terakhir
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLabels[] = $date->locale('id')->translatedFormat('D, d M');
            $revenueData[] = Transaction::whereDate('created_at', $date)
                ->where('status', 'selesai')
                ->sum('total_amount');
        }

        // Data untuk grafik top 5 produk terlaris
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

        $topProductLabels = $topProducts->map(function ($item) {
            return $item->product ? $item->product->name : 'Unknown';
        })->toArray();

        $topProductData = $topProducts->pluck('total_sold')->toArray();

        // Data untuk grafik status transaksi

        return view('admin.dashboard', compact(
            'recentTransactions',
            'newOrders',
            'todaysRevenue',
            'lowStockProducts',
            'lowStockProductsList',
            'totalRevenue',
            'revenueLabels',
            'revenueData',
            'topProductLabels',
            'topProductData'
        ));
    }
}
