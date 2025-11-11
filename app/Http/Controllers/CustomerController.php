<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Menampilkan dashboard pelanggan.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Ambil 3 transaksi terbaru pelanggan
        $latestTransactions = $user->transactions()->latest()->take(3)->get();



        return view('customer.dashboard', compact('user', 'latestTransactions'));
    }
}
