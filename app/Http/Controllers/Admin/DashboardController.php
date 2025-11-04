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
        

        $newOrders = Transaction::whereDate('created_at', Carbon::today())->count();

        // Pendapatan Hari Ini dari transaksi yang sukses

        $todaysRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->where('status', 'success')
            ->sum('total_amount');

        // Total Pelanggan Terdaftar
        $totalCustomers = User::where('role_id', 2)->count();

        // Jumlah Produk dengan Stok Menipis
        $lowStockProducts = Product::where('stock', '<', 10)->count();

        return view('admin.dashboard', compact('newOrders', 'todaysRevenue', 'totalCustomers', 'lowStockProducts'));
    }
}
