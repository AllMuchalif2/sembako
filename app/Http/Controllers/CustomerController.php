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

        // Coba ambil alamat dari transaksi terakhir sebagai "alamat utama"
        // Ini bisa dikembangkan lebih lanjut dengan membuat fitur manajemen alamat
        $primaryAddress = $user->transactions()->latest()->first()->shipping_address ?? 'Belum ada alamat tersimpan';

        return view('customer.dashboard', compact('user', 'latestTransactions', 'primaryAddress'));
    }
}
