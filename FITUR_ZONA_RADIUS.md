# Fitur Zona Radius Toko dengan Gratis Ongkir

Dokumentasi lengkap implementasi fitur zona radius toko menggunakan Leaflet Maps untuk sistem gratis ongkir berdasarkan jarak.

## 📋 Fitur Utama

1. **Peta Interaktif dengan Leaflet**

    - Marker toko (merah) menunjukkan lokasi toko
    - Lingkaran zona radius gratis ongkir (hijau, 10 km)
    - Marker pengiriman (biru) untuk lokasi pelanggan
    - Perhitungan jarak real-time

2. **Perhitungan Ongkir Otomatis**

    - Gratis ongkir jika dalam radius 10 km dari toko
    - Ongkir Rp 5.000 jika di luar radius
    - Update total pembayaran secara real-time

3. **Visualisasi Zona**
    - Lingkaran hijau menunjukkan zona gratis ongkir
    - Popup informatif pada setiap marker
    - Badge "GRATIS ONGKIR" untuk pesanan dalam zona

## 🗂️ File yang Dibuat/Dimodifikasi

### 1. Helper Class

**File:** `app/Helpers/LocationHelper.php`

```php
<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Hitung jarak antara dua koordinat menggunakan Haversine formula
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Implementasi Haversine formula
        // Return: jarak dalam meter
    }

    /**
     * Hitung ongkir berdasarkan jarak dari toko
     */
    public static function calculateShippingCost($distance, $freeShippingRadius = 10000, $shippingCost = 5000)
    {
        // Return: 0 jika dalam radius, atau biaya ongkir
    }

    /**
     * Get koordinat toko
     */
    public static function getStoreLocation()
    {
        return [
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'name' => 'Toko Sembako',
            'address' => 'Jakarta Pusat'
        ];
    }
}
```

### 2. Migration

**File:** `database/migrations/2025_11_25_120812_add_shipping_fields_to_transactions_table.php`

Menambahkan kolom:

-   `distance_from_store` (decimal): Jarak dari toko dalam meter
-   `shipping_cost` (integer): Biaya ongkir

### 3. Model Transaction

**File:** `app/Models/Transaction.php`

Menambahkan ke `$fillable`:

-   `distance_from_store`
-   `shipping_cost`

### 4. CheckoutController

**File:** `app/Http/Controllers/CheckoutController.php`

**Method `process()`** - Ditambahkan:

```php
// Hitung jarak dan ongkir
$storeLocation = \App\Helpers\LocationHelper::getStoreLocation();
$distance = \App\Helpers\LocationHelper::calculateDistance(
    $storeLocation['latitude'],
    $storeLocation['longitude'],
    $request->latitude,
    $request->longitude
);

// Hitung ongkir (gratis jika dalam radius 10km)
$shippingCost = \App\Helpers\LocationHelper::calculateShippingCost($distance, 10000, 5000);

$finalTotal = $subtotal - $discountAmount + $shippingCost;
```

### 5. View Checkout

**File:** `resources/views/checkout/index.blade.php`

**Fitur JavaScript:**

-   Marker toko dengan icon merah
-   Lingkaran zona radius (10 km)
-   Marker pengiriman dengan icon biru
-   Perhitungan jarak real-time menggunakan `map.distance()`
-   Update ongkir dan total otomatis saat lokasi dipilih

**Tampilan:**

-   Row ongkir di ringkasan pesanan
-   Badge "GRATIS" untuk gratis ongkir
-   Info jarak dari toko
-   Pesan zona (dalam/luar zona gratis ongkir)

### 6. View Admin Transaction Detail

**File:** `resources/views/admin/transactions/show.blade.php`

**Fitur:**

-   Menampilkan jarak dari toko
-   Menampilkan biaya ongkir
-   Badge "GRATIS ONGKIR" jika applicable
-   Peta dengan 3 layer:
    -   Marker toko (merah)
    -   Zona radius (hijau)
    -   Marker pengiriman (biru)
-   Auto fit bounds untuk menampilkan semua marker

## 🎨 Konfigurasi

### Koordinat Toko

Ubah di `app/Helpers/LocationHelper.php`:

```php
public static function getStoreLocation()
{
    return [
        'latitude' => -6.200000,    // Ganti dengan lat toko Anda
        'longitude' => 106.816666,  // Ganti dengan lng toko Anda
        'name' => 'Toko Sembako',
        'address' => 'Jakarta Pusat'
    ];
}
```

### Radius Gratis Ongkir

Ubah di `resources/views/checkout/index.blade.php`:

```javascript
const freeShippingRadius = 10000; // dalam meter (10 km)
```

### Biaya Ongkir

Ubah di `resources/views/checkout/index.blade.php`:

```javascript
const shippingCost = 5000; // Rp 5.000
```

## 📊 Alur Kerja

### Frontend (Checkout)

1. User membuka halaman checkout
2. Peta menampilkan:
    - Marker toko (merah)
    - Zona radius 10 km (lingkaran hijau)
3. User klik lokasi pengiriman di peta
4. Sistem menghitung jarak menggunakan Leaflet
5. Ongkir dihitung otomatis:
    - Jarak ≤ 10 km → Gratis
    - Jarak > 10 km → Rp 5.000
6. Total pembayaran update otomatis
7. Koordinat disimpan di hidden input

### Backend (Process)

1. Terima latitude & longitude dari form
2. Hitung jarak menggunakan Haversine formula
3. Tentukan ongkir berdasarkan jarak
4. Simpan ke database:
    - `distance_from_store`
    - `shipping_cost`
    - `total_amount` (sudah termasuk ongkir)

### Admin View

1. Admin buka detail transaksi
2. Peta menampilkan:
    - Marker toko (merah)
    - Zona radius (hijau)
    - Marker pengiriman (biru)
3. Info ditampilkan:
    - Jarak dari toko
    - Biaya ongkir
    - Badge gratis ongkir (jika applicable)

## 🎯 Contoh Penggunaan

### Skenario 1: Pelanggan Dalam Zona

-   Lokasi: 5 km dari toko
-   Ongkir: Rp 0 (GRATIS)
-   Badge: "GRATIS ONGKIR" muncul
-   Info: "✓ Lokasi Anda dalam zona gratis ongkir (5.00 km dari toko)"

### Skenario 2: Pelanggan Luar Zona

-   Lokasi: 15 km dari toko
-   Ongkir: Rp 5.000
-   Badge: Tidak muncul
-   Info: "Lokasi Anda di luar zona gratis ongkir (15.00 km dari toko)"

## 🔧 Troubleshooting

### Peta tidak muncul

-   Pastikan Leaflet CSS dan JS sudah di-load
-   Check console browser untuk error
-   Pastikan koordinat valid

### Ongkir tidak terhitung

-   Check JavaScript console
-   Pastikan marker sudah ditempatkan
-   Verify koordinat toko sudah benar

### Jarak tidak akurat

-   Haversine formula memberikan jarak "as the crow flies"
-   Untuk jarak jalan sebenarnya, gunakan routing API (Google Maps, OSRM)

## 📱 Responsive Design

-   Desktop: Peta 400px height
-   Mobile: Peta tetap interaktif
-   Touch-friendly marker placement

## 🚀 Pengembangan Lebih Lanjut

1. **Multiple Toko**

    - Hitung jarak ke toko terdekat
    - Pilih toko otomatis berdasarkan lokasi

2. **Ongkir Bertingkat**

    - 0-5 km: Gratis
    - 5-10 km: Rp 3.000
    - 10-20 km: Rp 5.000
    - > 20 km: Rp 10.000

3. **Routing**

    - Gunakan OSRM atau Google Directions API
    - Hitung jarak jalan sebenarnya
    - Estimasi waktu tempuh

4. **Geocoding**
    - Convert alamat text ke koordinat
    - Autocomplete alamat
    - Validasi alamat

## 📝 Catatan Penting

1. **Koordinat Toko**: Pastikan update koordinat toko sesuai lokasi sebenarnya
2. **Radius**: Sesuaikan radius dengan jangkauan pengiriman Anda
3. **Biaya**: Sesuaikan biaya ongkir dengan tarif Anda
4. **Database**: Jangan lupa jalankan migration

## 🔐 Security

-   Validasi koordinat di backend
-   Sanitize input
-   Rate limiting untuk API geocoding (jika digunakan)

## 📚 Referensi

-   [Leaflet Documentation](https://leafletjs.com/)
-   [Haversine Formula](https://en.wikipedia.org/wiki/Haversine_formula)
-   [OpenStreetMap](https://www.openstreetmap.org/)

---

**Dibuat:** 25 November 2025
**Versi:** 1.0.0
**Status:** Production Ready ✅
