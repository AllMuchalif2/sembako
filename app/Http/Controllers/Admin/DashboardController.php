<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // 5 transaksi terbaru dengan relasi user
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        // Pesanan baru yang sudah dibayar dan siap diproses
        $newOrders = Transaction::where('status', 'diproses')->count();

        $todaysRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_amount');

        // Total pelanggan (dengan role_id 2)
        $totalCustomers = User::where('role_id', 2)->count();

        // Jumlah produk dengan stok di bawah 10
        $lowStockProducts = Product::where('stock', '<', 10)->count();

        // Daftar produk dengan stok di bawah 10
        $lowStockProductsList = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        return view('admin.dashboard', compact('recentTransactions', 'newOrders', 'todaysRevenue', 'totalCustomers', 'lowStockProducts', 'lowStockProductsList'));
    }
}
