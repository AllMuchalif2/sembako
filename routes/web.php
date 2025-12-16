<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreSettingController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ReportController;

// rute landing page 
Route::get('/', [LandingController::class, 'index'])->name('landing');

// rute produk
Route::get('/products', [LandingController::class, 'products'])->name('products.index');
Route::get('/products/{product:slug}', [LandingController::class, 'show'])->name('product.show');

// rute keranjang belanja
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

// Rute yang memerlukan autentikasi 
Route::middleware('auth')->group(function () {
    // rute dashboard pelanggan
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

    //rute checkout 
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cod-success/{order_id}', [CheckoutController::class, 'codSuccess'])->name('checkout.cod-success');
    Route::get('/checkout/pay/{order_id}', [CheckoutController::class, 'pay'])->name('checkout.pay');

    // rute transaksi pelanggan
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('/transactions/{transaction}/complete', [TransactionController::class, 'markAsCompleted'])->name('transactions.complete');
    Route::patch('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // rute profil pelanggan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // rute promo
    Route::post('/promo/apply', [PromoController::class, 'apply'])->name('promo.apply');
    Route::post('/promo/remove', [PromoController::class, 'remove'])->name('promo.remove');
});

// rute admin (hanya admin yang bisa mengakses)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kategori, Produk, Promo
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('promos', AdminPromoController::class);

    // Manajemen Transaksi
    Route::get('transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::patch('transactions/{transaction}/status', [AdminTransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::patch('transactions/{transaction}/cancel', [AdminTransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::patch('transactions/{transaction}/confirm-cod', [AdminTransactionController::class, 'confirmCodOrder'])->name('transactions.confirmCod');
    Route::get('transactions/{transaction}/invoice', [AdminTransactionController::class, 'invoice'])->name('transactions.invoice');


    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
});

// rute owner (hanya owner yang bisa mengakses)
Route::middleware(['auth', 'role:owner'])->prefix('admin')->name('admin.')->group(function () {

    // Store Settings
    Route::get('store-settings', [StoreSettingController::class, 'edit'])->name('store-settings.edit');
    Route::put('store-settings', [StoreSettingController::class, 'update'])->name('store-settings.update');

    // Admin Management
    Route::resource('admins', AdminController::class);

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Rute callback Midtrans (tanpa auth/csrf untuk menerima notifikasi dari Midtrans)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

require __DIR__ . '/auth.php';

