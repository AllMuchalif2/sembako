<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru untuk ditampilkan di landing page
        $products = Product::latest()->take(8)->get();
        
        return view('welcome', compact('products'));
    }

    public function products(Request $request)
    {
        $categories = Category::all();
        $query = Product::query()->with('category');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        // Mengembalikan data produk sebagai JSON untuk modal
        return response()->json($product);
    }
}