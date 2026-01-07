<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Get all products with category
        $products = Product::with('category')->get();

        // Get sales data for each product
        $salesQuery = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(subtotal) as total_revenue'),
            DB::raw('COUNT(DISTINCT transaction_id) as transaction_count')
        )
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id');

        if ($request->filled('start_date')) {
            $salesQuery->whereHas('transaction', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $salesQuery->whereHas('transaction', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });
        }

        $salesData = $salesQuery->get()->keyBy('product_id');

        // Combine product data with sales data
        $productReports = $products->map(function ($product) use ($salesData) {
            $sales = $salesData->get($product->id);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'stock' => $product->stock,
                'price' => $product->price,
                'buy_price' => $product->buy_price,
                'stock_value' => $product->stock * $product->buy_price,
                'total_sold' => $sales->total_sold ?? 0,
                'total_revenue' => $sales->total_revenue ?? 0,
                'transaction_count' => $sales->transaction_count ?? 0,
                'profit_per_unit' => $product->price - $product->buy_price,
                'total_profit' => ($product->price - $product->buy_price) * ($sales->total_sold ?? 0),
            ];
        });

        // Filter only products that have been sold
        $productReports = $productReports->filter(function ($report) {
            return $report['total_sold'] > 0;
        });

        // Sort by total sold (descending)
        $productReports = $productReports->sortByDesc('total_sold')->values();

        // Calculate summary statistics
        $totalRevenue = $productReports->sum('total_revenue');
        $totalProfit = $productReports->sum('total_profit');
        $totalProductsSold = $productReports->sum('total_sold');

        return view('admin.product-reports.index', compact(
            'productReports',
            'totalRevenue',
            'totalProfit',
            'totalProductsSold'
        ));
    }

    public function print(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Get all products with category
        $productsQuery = Product::with('category');

        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        $products = $productsQuery->get();

        // Get sales data for each product
        $salesQuery = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(subtotal) as total_revenue'),
            DB::raw('COUNT(DISTINCT transaction_id) as transaction_count')
        )
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai');
            })
            ->groupBy('product_id');

        if ($request->filled('start_date')) {
            $salesQuery->whereHas('transaction', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $salesQuery->whereHas('transaction', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });
        }

        $salesData = $salesQuery->get()->keyBy('product_id');

        // Combine product data with sales data
        $productReports = $products->map(function ($product) use ($salesData) {
            $sales = $salesData->get($product->id);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'stock' => $product->stock,
                'price' => $product->price,
                'buy_price' => $product->buy_price,
                'stock_value' => $product->stock * $product->buy_price,
                'total_sold' => $sales->total_sold ?? 0,
                'total_revenue' => $sales->total_revenue ?? 0,
                'transaction_count' => $sales->transaction_count ?? 0,
                'profit_per_unit' => $product->price - $product->buy_price,
                'total_profit' => ($product->price - $product->buy_price) * ($sales->total_sold ?? 0),
            ];
        });

        // Filter only products that have been sold
        $productReports = $productReports->filter(function ($report) {
            return $report['total_sold'] > 0;
        });

        // Sort by total sold (descending)
        $productReports = $productReports->sortByDesc('total_sold')->values();

        // Calculate summary statistics
        $totalRevenue = $productReports->sum('total_revenue');
        $totalProfit = $productReports->sum('total_profit');
        $totalProductsSold = $productReports->sum('total_sold');

        // Get store settings
        $settings = StoreSetting::getSettings();
        $admin = auth()->user();

        return view('admin.product-reports.print', compact(
            'productReports',
            'totalRevenue',
            'totalProfit',
            'totalProductsSold',
            'settings',
            'admin'
        ));
    }
}
