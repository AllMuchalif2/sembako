<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // , 

        // params: [ // Custom validation messages
        //     'name.required' => 'Nama produk wajib diisi.',
        //     'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
        //     'name.unique' => 'Nama produk sudah ada, silakan gunakan nama lain.',
        //     'category_id.required' => 'Kategori wajib diisi.',
        //     'category_id.exists' => 'Kategori tidak valid.',
        //     'description.string' => 'Deskripsi harus berupa teks.',
        //     'price.required' => 'Harga wajib diisi.',
        //     'price.numeric' => 'Harga harus berupa angka.',
        //     'price.min' => 'Harga tidak boleh negatif.',
        //     'stock.required' => 'Stok wajib diisi.',
        //     'stock.integer' => 'Stok harus berupa angka bulat.',
        //     'stock.min' => 'Stok tidak boleh negatif.',
        //     'image.required' => 'Gambar tidak boleh kosong.',
        //     'image.image' => 'Gambar harus berupa gambar.',
        //     'image.mimes' => 'Hanya boleh format jpeg, png, jpg, gif, dan svg.',
        //     'image.max' => 'Ukuran gambar tidak boleh lebih dari 2048kb.',
        // ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $validated['slug'] . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $validated['image'] = 'products/' . $filename;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }
    public function show(Product $product)
    {
        $product->load('category');
        return response()->json($product);
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, Product $product)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 'sometimes' makes it optional on update
        ]);

        // , [ // Custom validation messages
        //     'name.required' => 'Nama produk wajib diisi.',
        //     'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
        //     'name.unique' => 'Nama produk sudah ada, silakan gunakan nama lain.',
        //     'category_id.required' => 'Kategori wajib diisi.',
        //     'category_id.exists' => 'Kategori tidak valid.',
        //     'description.string' => 'Deskripsi harus berupa teks.',
        //     'price.required' => 'Harga wajib diisi.',
        //     'price.numeric' => 'Harga harus berupa angka.',
        //     'price.min' => 'Harga tidak boleh negatif.',
        //     'stock.required' => 'Stok wajib diisi.',
        //     'stock.integer' => 'Stok harus berupa angka bulat.',
        //     'stock.min' => 'Stok tidak boleh negatif.',
        //     'image.image' => 'Gambar harus berupa gambar.',
        //     'image.mimes' => 'Hanya boleh format jpeg, png, jpg, gif, dan svg.',
        //     'image.max' => 'Ukuran gambar tidak boleh lebih dari 2048kb.',
        // ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                unlink(public_path('storage/' . $product->image));
            }

            $file = $request->file('image');

            $filename = $validated['slug'] . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('storage/products'), $filename);

            $validated['image'] = 'products/' . $filename;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy(Product $product)
    {

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $product->increment('stock', $request->stock);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan.');
    }
}