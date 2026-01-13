<?php

namespace App\Http\Controllers\Admin;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreSettingController extends Controller
{

    public function edit()
    {
        $settings = StoreSetting::getSettings();
        return view('admin.store-settings.edit', compact('settings'));
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_latitude' => 'required|numeric|between:-90,90',
            'store_longitude' => 'required|numeric|between:-180,180',
            'free_shipping_radius' => 'required|integer|min:0',
            'max_delivery_distance' => 'required|integer|min:0',
            'shipping_cost' => 'required|integer|min:0',
            'social_media_instagram' => 'nullable|url|max:255',
            'social_media_tiktok' => 'nullable|url|max:255',
            'social_media_whatsapp' => 'nullable|string|regex:/^62[0-9]{9,13}$/|max:15',
        ]);

        $settings = StoreSetting::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.store-settings.edit')
            ->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}
