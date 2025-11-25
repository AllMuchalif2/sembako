# Pengaturan Toko - Store Settings

## Deskripsi

Fitur ini memungkinkan admin untuk mengatur konfigurasi toko secara dinamis melalui database, termasuk:

-   Nama toko
-   Lokasi toko (latitude & longitude)
-   Radius gratis ongkir
-   Jarak maksimal pengiriman
-   Biaya ongkir

## File yang Dibuat/Dimodifikasi

### 1. Migration

-   `database/migrations/2025_11_25_122704_create_store_settings_table.php`
    -   Tabel untuk menyimpan pengaturan toko
    -   Hanya akan ada 1 record (ID = 1)

### 2. Model

-   `app/Models/StoreSetting.php`
    -   Model dengan method `getSettings()` untuk mengambil pengaturan
    -   Menggunakan `firstOrCreate` untuk memastikan selalu ada data

### 3. Controller

-   `app/Http/Controllers/Admin/StoreSettingController.php`
    -   `edit()` - Menampilkan form pengaturan
    -   `update()` - Menyimpan perubahan pengaturan

### 4. Views

-   `resources/views/admin/store-settings/edit.blade.php`
    -   Form untuk mengubah pengaturan toko
    -   Validasi input dengan feedback

### 5. Routes

-   `routes/web.php`
    -   `GET /admin/store-settings` - Halaman edit pengaturan
    -   `PUT /admin/store-settings` - Update pengaturan

### 6. Navigation

-   `resources/views/layouts/admin.blade.php`
    -   Menambahkan menu "Pengaturan Toko" di sidebar admin

### 7. Integration

-   `app/Http/Controllers/CheckoutController.php`
    -   Menggunakan `StoreSetting::getSettings()` untuk validasi jarak dan hitung ongkir
    -   Menghitung jarak dari toko ke lokasi pengiriman
    -   Menentukan ongkir berdasarkan pengaturan:
        -   Gratis jika dalam radius gratis ongkir
        -   Dikenakan biaya jika di luar radius
        -   Ditolak jika melebihi jarak maksimal

## Database Seeder

Jalankan seeder untuk data awal:

```bash
php artisan db:seed --class=StoreSettingSeeder
```

## Nilai Default

-   Nama Toko: "Toko Sembako"
-   Latitude: -6.200000
-   Longitude: 106.816666
-   Radius Gratis Ongkir: 10000 meter (10 km)
-   Jarak Maksimal: 50000 meter (50 km)
-   Biaya Ongkir: Rp 5.000

## Catatan Penting

-   Hanya ada 1 record pengaturan toko (ID = 1)
-   Semua nilai jarak dalam satuan METER
-   Koordinat menggunakan format desimal (bukan DMS)
-   Gunakan Google Maps untuk mendapatkan koordinat yang akurat
