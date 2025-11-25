<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Hitung jarak antara dua koordinat menggunakan Haversine formula
     * 
     * @param float $lat1 Latitude titik 1
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2); // Jarak dalam meter
    }

    /**
     * Hitung ongkir berdasarkan jarak dari toko
     * 
     * @param float $distance Jarak dalam meter
     * @param float $freeShippingRadius Radius gratis ongkir dalam meter (default 10000 = 10km)
     * @param int $shippingCost Biaya ongkir jika di luar radius (default 5000)
     * @return int Biaya ongkir
     */
    public static function calculateShippingCost($distance, $freeShippingRadius = null, $shippingCost = null)
    {
        if ($freeShippingRadius === null || $shippingCost === null) {
            $settings = \App\Models\StoreSetting::getSettings();
            $freeShippingRadius = $freeShippingRadius ?? $settings->free_shipping_radius;
            $shippingCost = $shippingCost ?? $settings->shipping_cost;
        }

        if ($distance <= $freeShippingRadius) {
            return 0; // Gratis ongkir
        }
        
        return $shippingCost; // Kena ongkir
    }

    /**
     * Get koordinat toko dari database
     * 
     * @return array ['latitude' => float, 'longitude' => float, 'name' => string]
     */
    public static function getStoreLocation()
    {
        $settings = \App\Models\StoreSetting::getSettings();
        
        return [
            'latitude' => $settings->store_latitude,
            'longitude' => $settings->store_longitude,
            'name' => $settings->store_name,
        ];
    }
}
