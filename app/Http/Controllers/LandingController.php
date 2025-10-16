<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru untuk ditampilkan di landing page
        $products = Product::latest()->take(8)->get();
        
        return view('welcome', compact('products'));
    }
}