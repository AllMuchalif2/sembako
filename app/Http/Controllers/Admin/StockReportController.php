<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\TransactionItem;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use App\Services\GroqService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|in:all,safe,low,out',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Get all products with category
        $productsQuery = Product::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        $products = $productsQuery->get();

        // Get sales data for each product
        $salesQuery = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('COUNT(DISTINCT transaction_id) as transaction_count')
        )
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id');

        $salesData = $salesQuery->get()->keyBy('product_id');

        // Combine product data with sales data and stock status
        $stockReports = $products->map(function ($product) use ($salesData) {
            $sales = $salesData->get($product->id);
            $totalSold = $sales->total_sold ?? 0;

            // Determine stock status
            if ($product->stock == 0) {
                $status = 'out';
                $statusLabel = 'Habis';
                $statusColor = 'red';
            } elseif ($product->stock <= 10) {
                $status = 'low';
                $statusLabel = 'Rendah';
                $statusColor = 'yellow';
            } else {
                $status = 'safe';
                $statusLabel = 'Aman';
                $statusColor = 'green';
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'stock' => $product->stock,
                'status' => $status,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'price' => $product->price,
                'buy_price' => $product->buy_price,
                'stock_value' => $product->stock * $product->buy_price,
                'total_sold' => $totalSold,
            ];
        });

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $stockReports = $stockReports->filter(function ($report) use ($request) {
                return $report['status'] === $request->status;
            });
        }

        // Sort by stock (ascending) - products with low stock first
        $stockReports = $stockReports->sortBy('stock')->values();

        // Calculate summary statistics
        $totalProducts = $stockReports->count();
        $totalStockValue = $stockReports->sum('stock_value');
        $lowStockCount = $stockReports->where('status', 'low')->count();
        $outOfStockCount = $stockReports->where('status', 'out')->count();

        // Get categories for filter
        $categories = Category::all();

        return view('admin.stock-reports.index', compact(
            'stockReports',
            'totalProducts',
            'totalStockValue',
            'lowStockCount',
            'outOfStockCount',
            'categories'
        ));
    }

    public function print(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|in:all,safe,low,out',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Get all products with category
        $productsQuery = Product::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        $products = $productsQuery->get();

        // Get sales data for each product
        $salesQuery = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('COUNT(DISTINCT transaction_id) as transaction_count')
        )
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id');

        $salesData = $salesQuery->get()->keyBy('product_id');

        // Combine product data with sales data and stock status
        $stockReports = $products->map(function ($product) use ($salesData) {
            $sales = $salesData->get($product->id);
            $totalSold = $sales->total_sold ?? 0;

            // Determine stock status
            if ($product->stock == 0) {
                $status = 'out';
                $statusLabel = 'Habis';
            } elseif ($product->stock <= 10) {
                $status = 'low';
                $statusLabel = 'Rendah';
            } else {
                $status = 'safe';
                $statusLabel = 'Aman';
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'stock' => $product->stock,
                'status' => $status,
                'status_label' => $statusLabel,
                'price' => $product->price,
                'buy_price' => $product->buy_price,
                'stock_value' => $product->stock * $product->buy_price,
                'total_sold' => $totalSold,
            ];
        });

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $stockReports = $stockReports->filter(function ($report) use ($request) {
                return $report['status'] === $request->status;
            });
        }

        // Sort by stock (ascending)
        $stockReports = $stockReports->sortBy('stock')->values();

        // Calculate summary statistics
        $totalProducts = $stockReports->count();
        $totalStockValue = $stockReports->sum('stock_value');
        $lowStockCount = $stockReports->where('status', 'low')->count();
        $outOfStockCount = $stockReports->where('status', 'out')->count();

        // Get store settings
        $settings = StoreSetting::getSettings();
        $admin = auth()->user();

        return view('admin.stock-reports.print', compact(
            'stockReports',
            'totalProducts',
            'totalStockValue',
            'lowStockCount',
            'outOfStockCount',
            'settings',
            'admin'
        ));
    }

    public function analyze(Request $request, GroqService $groq)
    {
        $validator = \Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['analysis' => 'Parameter tidak valid.']);
        }

        // Get all products
        $productsQuery = Product::with('category');

        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        $products = $productsQuery->get();

        if ($products->count() == 0) {
            return response()->json(['analysis' => 'Tidak ada data produk untuk dianalisa.']);
        }

        // Get sales data
        $salesQuery = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold')
        )
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id');

        $salesData = $salesQuery->get()->keyBy('product_id');

        // Categorize products
        $outOfStock = [];
        $lowStock = [];
        $safeStock = [];

        foreach ($products as $product) {
            $totalSold = $salesData->get($product->id)->total_sold ?? 0;
            
            if ($product->stock == 0) {
                $outOfStock[] = [
                    'name' => $product->name,
                    'total_sold' => $totalSold
                ];
            } elseif ($product->stock <= 10) {
                $lowStock[] = [
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'total_sold' => $totalSold
                ];
            } else {
                $safeStock[] = [
                    'name' => $product->name,
                    'stock' => $product->stock
                ];
            }
        }

        $summaryData = [
            'total_produk' => $products->count(),
            'produk_habis' => count($outOfStock),
            'produk_stok_rendah' => count($lowStock),
            'produk_aman' => count($safeStock),
            'daftar_habis' => array_slice($outOfStock, 0, 5),
            'daftar_stok_rendah' => array_slice($lowStock, 0, 5),
        ];

        $prompt = "Berikut adalah ringkasan data stok toko Sembako:\n" . json_encode($summaryData, JSON_PRETTY_PRINT);
        $prompt .= "\n\nTolong berikan analisa singkat (maksimal 3 paragraf) dalam Bahasa Indonesia gaya profesional tapi mudah dimengerti. ";
        $prompt .= "Fokus pada: 1) Produk yang perlu segera direstock, 2) Rekomendasi jumlah restock berdasarkan penjualan, 3) Peringatan untuk produk yang habis atau hampir habis.";

        // Call AI
        $analysis = $groq->chat($prompt, 'Kamu adalah manajer inventori senior di toko retail sembako.');

        return response()->json(['analysis' => $analysis]);
    }
}
