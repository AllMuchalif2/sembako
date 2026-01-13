<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {

        // Ambil 8 produk terbaru untuk ditampilkan di landing page
        $products = Product::latest()->take(4)->get();
        $today = now()->toDateString();

        Promo::where('start_date', '>', $today)->update(['status' => 'inactive']);

        Promo::where('end_date', '<', $today)->update(['status' => 'inactive']);

        Promo::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->update(['status' => 'active']);

        // Ambil kembali promo setelah potensi update status
        $promos = Promo::where('status', 'active')->get();

        // Ambil store settings untuk footer
        $settings = StoreSetting::getSettings();

        return view('welcome', [
            'products' => $products,
            'promos' => $promos,
            'settings' => $settings,
        ]);
    }


}