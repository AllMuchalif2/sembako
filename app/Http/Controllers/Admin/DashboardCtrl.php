<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardCtrl extends Controller
{
    //
    public function index()
    {
        // Asumsi:
        // - Status 'SUCCESS' menandakan pesanan yang sudah selesai/dibayar.
        // - Role ID 2 adalah untuk 'Customer'.
        // - Stok menipis jika jumlahnya di bawah 10.

        // Total Pesanan Baru Hari Ini

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
