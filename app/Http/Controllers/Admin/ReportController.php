<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Clone query for total calculation before pagination
        $totalRevenue = $query->sum('total_amount');

        $transactions = $query->latest()->get(); // Get all for report without pagination usually, or paginate if needed. Let's get all for now for accurate total in list? Or paginate and show total of *filtered* data? Use get() for reports usually creates a clearer picture.

        return view('admin.reports.index', compact('transactions', 'totalRevenue'));
    }

    public function print(Request $request)
    {
        $query = Transaction::with('user')
            ->where('status', 'selesai');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRevenue = $query->sum('total_amount');
        $transactions = $query->latest()->get();

        return view('admin.reports.print', compact('transactions', 'totalRevenue'));
    }
}
