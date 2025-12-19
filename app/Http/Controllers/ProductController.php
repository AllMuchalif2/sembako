<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function products(Request $request)
    {
        // Mengambil kategori beserta jumlah produk di dalamnya
        $categories = Category::withCount('products')->get();
        $query = Product::query()->with('category');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Logika untuk sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest(); // 'latest'
                break;
        }

        $products = $query->paginate(20)->withQueryString();

        return view('products.index', compact('products', 'categories', 'sort'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        // Mengembalikan data produk sebagai JSON untuk modal
        return response()->json($product);
    }
}
