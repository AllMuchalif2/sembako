# 🛒 MyMart - Aplikasi Sembako Online

Aplikasi e-commerce sembako modern dengan fitur lengkap untuk manajemen toko online.

## 📋 Deskripsi

MyMart adalah aplikasi e-commerce berbasis web yang dirancang khusus untuk toko sembako. Aplikasi ini dilengkapi dengan sistem manajemen produk, transaksi, promo, pembayaran terintegrasi dengan Midtrans, analisis AI menggunakan Groq, dan fitur Progressive Web App (PWA) untuk pengalaman mobile yang optimal.

---

## ✨ Fitur Utama

### 👥 Untuk Pelanggan

-   🏪 **Katalog Produk** - Browse produk dengan filter kategori dan pencarian
-   🛒 **Keranjang Belanja** - Manajemen keranjang dengan AJAX real-time
-   🎁 **Sistem Promo** - Kode promo dengan validasi otomatis
-   💳 **Multi Payment** - Midtrans (berbagai metode) dan Cash on Delivery (COD)
-   📍 **Lokasi Checkout** - Integrasi peta Leaflet untuk pilih lokasi pengiriman
-   📦 **Tracking Pesanan** - Pantau status pesanan real-time
-   💬 **AI Chatbot** - Customer service otomatis dengan Groq AI
-   📱 **PWA Support** - Install sebagai aplikasi mobile

### 👨‍💼 Untuk Admin

-   📊 **Dashboard Analytics** - 4 metric cards (Total Pendapatan, Pendapatan Hari Ini, Pesanan Baru, Stok Menipis) + 2 grafik analitik (Pendapatan 7 Hari, Top 5 Produk Terlaris)
-   📦 **Manajemen Produk** - CRUD produk dengan fitur restock
-   🏷️ **Manajemen Kategori** - Organisasi produk berdasarkan kategori
-   🎫 **Manajemen Promo** - Buat dan kelola kode promo
-   💰 **Manajemen Transaksi** - Update status, konfirmasi COD, cetak invoice
-   📈 **Laporan Keuangan** - Filter laporan berdasarkan tanggal dengan analisis AI
-   📊 **Laporan Penjualan** - Laporan produk terlaris dengan filter tanggal, clickable summary cards
-   📦 **Laporan Stok** - Monitor stok produk dengan filter kategori dan status, clickable summary cards
-   🖨️ **Cetak PDF** - Generate laporan dalam format PDF (hitam putih)

### 🔐 Untuk Owner (Super Admin)

-   ⚙️ **Pengaturan Toko** - Konfigurasi nama, lokasi, ongkir, radius gratis ongkir, social media links
-   📱 **Social Media Integration** - Instagram, TikTok, WhatsApp terintegrasi di footer dan AI chatbot
-   👥 **Manajemen Admin** - CRUD admin, toggle status, reset password
-   📜 **Activity Logs** - Audit trail semua aktivitas admin menggunakan Spatie Activity Log
-   🎨 **Grouped Sidebar** - Menu admin terorganisir dengan collapsible groups

---

## 🚀 Instalasi

### Prasyarat

-   PHP >= 8.2
-   Composer
-   Node.js & npm
-   MySQL/MariaDB
-   Web Server (Apache/Nginx) atau Laravel Valet/Laragon

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd my-mart
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database**

    Edit file `.env` dan sesuaikan konfigurasi database:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sembako
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Konfigurasi API Keys**

    Tambahkan API keys di file `.env`:

    ```env
    # Midtrans Payment Gateway
    MIDTRANS_MERCHANT_ID=your_merchant_id
    MIDTRANS_CLIENT_KEY=your_client_key
    MIDTRANS_SERVER_KEY=your_server_key
    MIDTRANS_IS_PRODUCTION=false

    # Groq AI (untuk chatbot & analisis)
    GROQ_API_KEY=your_groq_api_key
    ```

    **Cara mendapatkan API Keys:**

    - **Midtrans**: Daftar di [https://dashboard.midtrans.com](https://dashboard.midtrans.com)
    - **Groq AI**: Daftar di [https://console.groq.com](https://console.groq.com)

6. **Migrasi Database & Seeding**

    ```bash
    php artisan migrate --seed
    ```

    Seeder akan membuat:

    - 2 Role: Admin & Customer
    - User Owner default (email: `owner@mymart.com`, password: `password`)
    - User Admin default (email: `admin@mymart.com`, password: `password`)
    - Kategori produk sample
    - Produk sample
    - Promo sample
    - Store settings default

7. **Build Assets**

    ```bash
    npm run build
    ```

8. **Jalankan Aplikasi**

    **Untuk Development:**

    ```bash
    composer run dev
    ```

    Perintah ini akan menjalankan:

    - Laravel development server (http://localhost:8000)
    - Queue worker
    - Vite dev server

    **Atau jalankan manual:**

    ```bash
    php artisan serve
    php artisan queue:work
    npm run dev
    ```

9. **Akses Aplikasi**
    - **Frontend**: http://localhost:8000
    - **Admin Panel**: http://localhost:8000/admin/dashboard
    - **Login Owner**: owner@mymart.com / password
    - **Login Admin**: admin@mymart.com / password

---

## 🛠️ Tech Stack

### Backend

-   **Laravel 12.x** - Framework PHP berbasis MVC
-   **Laravel Breeze** - Starter kit autentikasi
-   **Spatie Activity Log** - Audit trail untuk aktivitas admin/owner
-   **Midtrans PHP SDK** - Payment gateway integration

### Database

-   **MySQL** - Sistem manajemen basis data
-   **Laravel Migrations** - Version control untuk database schema

### Frontend & UI

-   **Tailwind CSS 3.x** - Framework CSS utility-first
-   **AlpineJS** - Framework JavaScript minimalis untuk interaktivitas
-   **Axios** - HTTP client untuk AJAX requests
-   **Chart.js 4.x** - Library visualisasi data untuk grafik dashboard
-   **Simple-DataTables** - Plugin tabel interaktif (search, sort, pagination)
-   **Leaflet.js** - Library peta untuk fitur lokasi checkout
-   **Tippy.js** - Library tooltip interaktif

### Build Tools

-   **Vite** - Modern bundler untuk asset compilation
-   **npm** - Package manager untuk dependencies frontend
-   **Composer** - Package manager untuk dependencies PHP

### AI & External Services

-   **Groq API** - Large Language Model untuk chatbot & analisis laporan
-   **Midtrans** - Payment gateway (Credit Card, E-Wallet, Bank Transfer, dll)

### PWA (Progressive Web App)

-   **Laravel PWA** - Package untuk manifest.json dan service worker
-   **Service Worker** - Offline support dan caching

---

## 📁 Struktur Database

### Tabel Utama

| Tabel               | Deskripsi                                    |
| ------------------- | -------------------------------------------- |
| `users`             | Data pengguna (customer, admin, owner)       |
| `roles`             | Role pengguna (1=Owner, 2=Customer, 3=Admin) |
| `categories`        | Kategori produk                              |
| `products`          | Data produk sembako                          |
| `transactions`      | Data transaksi pembelian                     |
| `transaction_items` | Detail item dalam transaksi                  |
| `promos`            | Kode promo dan diskon                        |
| `promo_usages`      | Riwayat penggunaan promo                     |
| `store_settings`    | Konfigurasi toko (nama, lokasi, ongkir, social media) |
| `activity_log`      | Log aktivitas admin/owner (Spatie)           |
| `sessions`          | Session management                           |
| `cache`             | Application cache                            |
| `jobs`              | Queue jobs                                   |

### Relasi Penting

-   `users` → `roles` (belongsTo)
-   `products` → `categories` (belongsTo)
-   `transactions` → `users` (belongsTo)
-   `transactions` → `promos` (belongsTo, nullable)
-   `transaction_items` → `transactions` (belongsTo)
-   `transaction_items` → `products` (belongsTo)
-   `promo_usages` → `promos` (belongsTo)
-   `promo_usages` → `users` (belongsTo)

---

## 📖 Dokumentasi

Untuk dokumentasi lengkap alur fungsional aplikasi, silakan lihat:

👉 **[docs.md](docs.md)** - Dokumentasi Alur Fungsional Lengkap

Dokumentasi mencakup:

-   Alur Autentikasi (Login, Register, Logout)
-   Alur Publik & Katalog Produk
-   Alur Keranjang & Transaksi
-   Alur Dashboard & Fitur Admin
-   Alur Owner (Super Admin)
-   Fitur AI & Chatbot
-   Progressive Web App (PWA)

---

<div align="center">

**⭐ Jangan lupa berikan star jika project ini bermanfaat! ⭐**

</div>
