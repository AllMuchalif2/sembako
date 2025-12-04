<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreSetting::updateOrCreate(
            ['id' => 1],
            [
                'store_name' => 'My Mart',
                'store_latitude' => -6.87499575,
                'store_longitude' => 109.66500781,
                'free_shipping_radius' => 5000, // 5 km
                'max_delivery_distance' => 10000, // 10 km
                'shipping_cost' => 5000, // Rp 5.000
            ]
        );
    }
}
