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
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        $newOrders = Transaction::where('status', 'diproses')->count();

        $todaysRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_amount');

        $totalCustomers = User::where('role_id', 2)->count();

        $lowStockProducts = Product::where('stock', '<', 10)->count();

        $lowStockProductsList = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        return view('admin.dashboard', compact('recentTransactions', 'newOrders', 'todaysRevenue', 'totalCustomers', 'lowStockProducts', 'lowStockProductsList'));
    }
}
