<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $query = Activity::with('causer', 'subject');

        if ($request->filled('causer_id')) {
            if ($request->causer_id === 'null') {
                $query->whereNull('causer_id');
            } else {
                $query->where('causer_id', $request->causer_id);
            }
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activities = $query->latest()->paginate(10)->withQueryString();

        // Get list of potential causers (Admins and Owners) for the filter dropdown
        $admins = User::whereIn('role_id', [1, 2])->get();

        return view('admin.activity_logs.index', compact('activities', 'admins'));
    }
}
