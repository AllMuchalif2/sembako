<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreSettingController extends Controller
{
    /**
     * Show the form for editing store settings.
     */
    public function edit()
    {
        $settings = \App\Models\StoreSetting::getSettings();
        return view('admin.store-settings.edit', compact('settings'));
    }

    /**
     * Update the store settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_latitude' => 'required|numeric|between:-90,90',
            'store_longitude' => 'required|numeric|between:-180,180',
            'free_shipping_radius' => 'required|integer|min:0',
            'max_delivery_distance' => 'required|integer|min:0',
            'shipping_cost' => 'required|integer|min:0',
        ]);

        $settings = \App\Models\StoreSetting::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.store-settings.edit')
            ->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}
