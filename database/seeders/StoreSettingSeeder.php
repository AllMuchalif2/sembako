<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\StoreSetting::updateOrCreate(
            ['id' => 1],
            [
                'store_name' => 'Toko Sembako',
                'store_latitude' => -6.200000,
                'store_longitude' => 106.816666,
                'free_shipping_radius' => 10000, // 10 km
                'max_delivery_distance' => 50000, // 50 km
                'shipping_cost' => 5000, // Rp 5.000
            ]
        );
    }
}
