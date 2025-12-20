## Tech Stack — MyMart Sembako App

---

### Backend

-   Laravel — Framework PHP berbasis MVC.
-   Laravel Breeze — Starter kit autentikasi menggunakan Blade dan route auth standar.

---

### Database

-   MySQL — Sistem manajemen basis data untuk seluruh fitur.
-   Spatie Activity Log — Mencatat aktivitas pengguna (Admin & Owner) untuk audit trail.

---

### Frontend & UI

-   Tailwind CSS — Framework CSS utility-first untuk styling.
-   AlpineJS — Framework JavaScript minimalis untuk interaktivitas UI.
-   Axios — Library untuk HTTP requests (AJAX).
-   Simple-DataTables — Plugin tabel interaktif (search, sort, pagination) di halaman admin.
-   Leaflet.js — Library peta untuk fitur lokasi checkout (latitude dan longitude).
-   Tippy.js — Library untuk tooltip interaktif.

---

### Build Tools

-   Vite — Bundler modern untuk compiling asset (JS & CSS).
-   npm — Package manager untuk dependency frontend.
-   Composer — Package manager untuk dependency PHP/Laravel.

---

### Testing

-   PHPUnit — Framework pengujian yang digunakan oleh Laravel.

---

# Dokumentasi Alur Fungsional Aplikasi

## 1. Alur Autentikasi (Auth)

### Alur Login Pengguna

-   **Rute**
    -   GET /login
    -   POST /login
-   **Middleware**: guest
-   **Controller**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
    -   Metode: `create()`, `store()`
-   **Request**: `app/Http/Requests/Auth/LoginRequest.php`
-   **Model**: `app/Models/User.php`
-   **View**: `resources/views/auth/login.blade.php`
-   **Database**: `users` (email, password)

### Alur "Ingat Saya" (Remember Me)

-   **Terkait**: Alur Login
-   **View**: `resources/views/auth/login.blade.php` (input `remember`)
-   **Database**: `users` (remember_token)

### Alur Registrasi Pengguna

-   **Rute**
    -   GET /register
    -   POST /register
-   **Middleware**: guest
-   **Controller**: `app/Http/Controllers/Auth/RegisteredUserController.php`
    -   Metode: `create()`, `store()`
-   **Model**: `app/Models/User.php` (booted: role_id = 2)
-   **View**: `resources/views/auth/register.blade.php`
-   **Database**: `users` (name, email, password, role_id)

### Alur Logout Pengguna

-   **Rute**: POST /logout
-   **Middleware**: auth
-   **Controller**: `AuthenticatedSessionController@destroy`
-   **View**: dipicu dari `resources/views/layouts/navigation.blade.php`
-   **Database**: tidak ada (session)

---

## 2. Alur Publik & Katalog

### Alur Menampilkan Halaman Utama (Homepage)

-   **Rute**: GET /
-   **Controller**: `LandingController@index`
-   **Model**: `Product`, `Promo`
-   **View**: `welcome.blade.php`
-   **Database**: products (read), promos (read where status = active)

### Alur Menampilkan Katalog Produk

-   **Rute**: GET /products
-   **Controller**: `ProductController@index`
-   **Model**: `Product`, `Category`
-   **View**: `products/index.blade.php`
-   **Database**: products (read + filter/sort), categories (read)

### Alur Menampilkan Modal Produk (Quick View)

-   **Rute**: GET /products/{product:slug}
-   **Controller**: `ProductController@show`
-   **Model**: `Product`
-   **JavaScript**: `resources/js/app.js`
-   **View Komponen**: `components/modal.blade.php`
-   **Database**: products (read), categories (via load)

---

## 3. Alur Keranjang & Transaksi (User)

### Alur Manajemen Keranjang

-   **Rute**
    -   POST /cart/add
    -   PATCH /cart/update/{id}
    -   DELETE /cart/remove/{id}
    -   GET /cart/summary
-   **Controller**: `CartController`
    -   Metode: `add`, `update`, `remove`, `summary`
-   **JavaScript**: `resources/js/cart.js`
-   **Model**: `Product`
-   **View**: `cart/index.blade.php`
-   **Database**: tidak ada (session)

### Alur Checkout (Termasuk Peta & Promo)

-   **Rute**
    -   POST /promo/apply
    -   POST /promo/remove
    -   GET /checkout
    -   POST /checkout
-   **Middleware**: auth
-   **Controller**
    -   `PromoController`: `apply`, `remove`
    -   `CheckoutController`: `index`, `process`
-   **Model**
    -   `Promo`, `Transaction`, `TransactionItem`, `Product`, `PromoUsage`
-   **View**: `checkout/index.blade.php`
-   **JavaScript**: integrasi Leaflet (lat, long)
-   **Database**
    -   promos (read, update times_used)
    -   promo_usages (write)
    -   transactions (write)
    -   transaction_items (write)
    -   products (update stock)

### Alur Pembayaran Midtrans

-   **Rute**
    -   POST /checkout
    -   POST /midtrans/callback
    -   GET /checkout/success
-   **Controller**: `CheckoutController@process`, `PaymentController@callback`, `CheckoutController@success`
-   **Config**: `config/midtrans.php`
-   **View**
    -   `checkout/payment.blade.php`
    -   `checkout/success.blade.php`
-   **Database**
    -   transactions (update snap_token, status, payment_status)
    -   products (restore stock jika gagal)

### Alur Pembayaran COD (Cash on Delivery)

-   **Rute**
    -   POST /checkout (dengan payment_method = cod)
    -   GET /checkout/cod-success/{order_id}
-   **Controller**: `CheckoutController@process`, `codSuccess`
-   **View**: `checkout/cod-success.blade.php`
-   **Database**
    -   transactions (create dengan payment_method = cod, payment_status = unpaid)

### Alur Retry Payment (Bayar Ulang)

-   **Rute**
    -   GET /checkout/pay/{order_id}
-   **Middleware**: auth
-   **Controller**: `PaymentController@pay`
-   **View**: `checkout/payment.blade.php`
-   **Database**
    -   transactions (update snap_token baru)

---

## 4. Alur Pengguna (Customer)

### Alur Dasbor Pengguna

-   **Rute**: GET /dashboard
-   **Middleware**: auth
-   **Controller**: `CustomerController@dashboard`
-   **Model**: `Transaction`
-   **View**: `customer/dashboard.blade.php`
-   **Database**: transactions (read by user_id)

### Alur Profil Pengguna

-   **Rute**
    -   GET /profile
    -   PATCH /profile
    -   DELETE /profile
    -   PUT /password
-   **Middleware**: auth
-   **Controller**
    -   `ProfileController`: `edit`, `update`, `destroy`
    -   `PasswordController@update`
-   **Request**: `ProfileUpdateRequest`
-   **View**: `profile/edit.blade.php`
-   **Database**: users (update & delete)

### Alur Riwayat Transaksi Pengguna

-   **Rute**
    -   GET /transactions
    -   GET /transactions/{transaction}
    -   PATCH /transactions/{transaction}/complete
    -   PATCH /transactions/{transaction}/cancel
-   **Middleware**: auth
-   **Controller**: `TransactionController`
-   **Model**: `Transaction`
-   **View**: `transactions/index.blade.php`, `transactions/show.blade.php`
-   **Database**: transactions (read, update status, append notes)

---

## 5. Alur Admin

### Alur Dasbor Admin

-   **Rute**: GET /admin/dashboard
-   **Middleware**: auth, role:admin
-   **Controller**: `Admin\DashboardController@index`
-   **Model**: `User`, `Transaction`, `Product`
-   **View**: `admin/dashboard.blade.php`
-   **Database**: users, transactions, products (read)

### Admin: CRUD Kategori

-   **Rute**: RESOURCE /admin/categories
-   **Middleware**: auth, role:admin
-   **Controller**: `Admin\CategoryController`
-   **Model**: `Category`
-   **View**: index/create/edit
-   **Database**: categories (CRUD)

### Admin: CRUD Produk

-   **Rute**: 
    - RESOURCE /admin/products
    - PATCH /admin/products/{product}/restock

-   **Middleware**: auth, role:admin
-   **Controller**: `Admin\ProductController`
-   **Model**: `Product`
-   **View**: index/create/edit
-   **Database**: products (CRUD)
-   **Fitur Ekstra**:
    -   Restock Produk (Update stok tambahan)

### Admin: CRUD Promo

-   **Rute**: RESOURCE /admin/promos
-   **Middleware**: auth, role:admin
-   **Controller**: `Admin\PromoController`
-   **Model**: `Promo`
-   **View**: index/create/edit
-   **Database**: promos (CRUD)

### Admin: Manajemen Transaksi

-   **Rute**
    -   GET /admin/transactions
    -   GET /admin/transactions/{transaction}
    -   PATCH /admin/transactions/{transaction}/status
    -   PATCH /admin/transactions/{transaction}/cancel
    -   PATCH /admin/transactions/{transaction}/confirm-cod
    -   GET /admin/transactions/{transaction}/invoice
-   **Middleware**: auth, role:admin
-   **Controller**: `Admin\TransactionController`
-   **Model**: `Transaction`
-   **View**: index/show/invoice
-   **Database**: transactions (read & update)

### Admin: Profil Admin

-   **Rute**
    -   GET /admin/profile
    -   PATCH /admin/profile
-   **Middleware**: auth, role:admin
-   **Controller**: `ProfileController`
-   **Model**: `User`
-   **View**: `profile/edit.blade.php`
-   **Database**: users (read & update)

### Admin: Laporan & Analisis AI

-   **Rute**
    -   GET /admin/reports
    -   POST /admin/reports/analyze (AI Insight)
    -   GET /admin/reports/print
-   **Controller**: `Admin\ReportController`
-   **Fitur**:
    -   Filter laporan berdasarkan tanggal
    -   Cetak PDF
    -   Analisis ringkas pendapatan menggunakan AI (Groq API)

---

## 6. Alur Owner (Super Admin)

### Alur Pengaturan Toko (Store Settings)

-   **Rute**
    -   GET /admin/store-settings
    -   PUT /admin/store-settings
-   **Middleware**: auth, role:owner
-   **Controller**: `Admin\StoreSettingController`
-   **Model**: `StoreSetting`
-   **View**: `admin/store-settings/edit.blade.php`
-   **Database**: store_settings (update)
-   **Fitur**:
    -   Nama toko
    -   Koordinat toko (latitude, longitude)
    -   Radius gratis ongkir
    -   Jarak maksimal pengiriman
    -   Biaya pengiriman

### Owner: CRUD Admin

-   **Rute**: RESOURCE /admin/admins
-   **Middleware**: auth, role:owner
-   **Controller**: `Admin\AdminController`
-   **Model**: `User`, `Role`
-   **View**: index/create/edit
-   **Database**: users (CRUD untuk role admin)
-   **Fitur**:
    -   Tambah admin baru
    -   Edit data admin
    -   Hapus admin (dengan proteksi tidak bisa hapus diri sendiri)

### Owner: Log Aktivitas

-   **Rute**: GET /admin/activity-logs
-   **Middleware**: auth, role:owner
-   **Controller**: `Admin\ActivityLogController`
-   **View**: `admin/activity-logs/index.blade.php`
-   **Database**: activity_log (read)
-   **Fitur**: Melihat riwayat aksi create, update, delete yang dilakukan user.

---

## 7. Fitur AI & Chatbot

### Chatbot Pelanggan (Groq AI)

-   **Rute**: POST /ai/chat
-   **Controller**: `AiChatController@handleChat`
-   **Teknologi**: Groq API (LLM)
-   **Fungsi**: Menjawab pertanyaan pelanggan secara otomatis terkait produk atau layanan.
