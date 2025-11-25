<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru untuk ditampilkan di landing page
        $products = Product::latest()->take(8)->get();
        $promos = Promo::where('status', 'active')->get();
        // Perbarui status promo jika end_date sudah terlewat
        foreach ($promos as $promo) {
            if ($promo->status === 'active' && now()->isAfter($promo->end_date)) {
                $promo->status = 'expired';
                $promo->save();
            }
        }
        // Ambil kembali promo setelah potensi update status
        $promos = Promo::where('status', 'active')->get();


        return view('welcome', [
            'products' => $products,
            'promos' => $promos,
        ]);
    }

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