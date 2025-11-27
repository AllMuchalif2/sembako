<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{

    public function index()
    {
        $today = now()->toDateString();

        Promo::where('start_date', '>', $today)->update(['status' => 'inactive']);

        Promo::where('end_date', '<', $today)->update(['status' => 'inactive']);

        Promo::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->update(['status' => 'active']);

        $promos = Promo::latest()->get();
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promos,code',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'limit_per_user' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['limit_per_user'] = $request->has('limit_per_user');

        Promo::create($validated);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }


    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }


    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promos,code,' . $promo->id,
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'limit_per_user' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['limit_per_user'] = $request->has('limit_per_user');

        $promo->update($validated);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }


    public function destroy(Promo $promo)
    {
        if ($promo->usages()->exists()) {
            return redirect()->back()->with('error', 'Promo tidak dapat dihapus karena sudah pernah digunakan.');
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus.');
    }
}