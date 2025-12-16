<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::updateOrCreate(
            ['id' => 2],
            [
                'code' => 'mymart',
                'description' => 'Diskon pembelian pertama di My Mart',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => 20000,
                'min_purchase' => 50000,
                'start_date' => '2024-01-01',
                'end_date' => '2026-12-31',
                'usage_limit' => 100,
                'times_used' => 0,
                'limit_per_user' => true,
                'status' => 'active',
            ]
        );
    }
}
