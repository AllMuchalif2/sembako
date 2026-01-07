<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_address',
        'store_latitude',
        'store_longitude',
        'free_shipping_radius',
        'max_delivery_distance',
        'shipping_cost',
    ];

    protected $casts = [
        'store_latitude' => 'decimal:8',
        'store_longitude' => 'decimal:8',
        'free_shipping_radius' => 'integer',
        'max_delivery_distance' => 'integer',
        'shipping_cost' => 'integer',
    ];

    /**
     * Get the store settings (always returns the first record)
     */
    public static function getSettings()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'store_name' => 'Toko Sembako',
                'store_address' => null,
                'store_latitude' => -6.200000,
                'store_longitude' => 106.816666,
                'free_shipping_radius' => 10000,
                'max_delivery_distance' => 50000,
                'shipping_cost' => 5000,
            ]
        );
    }
}
