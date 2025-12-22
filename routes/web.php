<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\StoreSettingController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

// PWA Routes - Serve via PHP to bypass InfinityFree static file restrictions
Route::get('/manifest.json', function () {
    $manifest = [
        "name" => "My - Mart",
        "short_name" => "MyMart",
        "start_url" => "/",
        "background_color" => "#ffffff",
        "description" => "Aplikasi belanja sembako murah dan terpercaya.",
        "display" => "standalone",
        "theme_color" => "#ffffff",
        "icons" => [
            [
                "src" => "images/logo.png",
                "sizes" => "512x512",
                "type" => "image/png",
                "purpose" => "any maskable"
            ]
        ],
        "screenshots" => [
            [
                "src" => "images/desktop.png",
                "sizes" => "1920x1080",
                "type" => "image/png",
                "form_factor" => "wide",
                "label" => "Tampilan Desktop MyMart"
            ],
            [
                "src" => "images/mobile.png",
                "sizes" => "1080x1920",
                "type" => "image/png",
                "form_factor" => "narrow",
                "label" => "Tampilan Mobile MyMart"
            ]
        ]
    ];
    return response()->json($manifest);
});

Route::get('/sw.js', function () {
    $script = <<<'JS'
const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    '/offline.html'
];

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    if (!request.url.startsWith('http')) {
        return Promise.resolve();
    }
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            return cache.put(request, response);
        });
    });
};

const returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    if(!event.request.url.startsWith('http')){
        event.waitUntil(addToCache(event.request));
    }
});
JS;
    return response($script)->header('Content-Type', 'application/javascript');
});

// rute landing page 
Route::get('/', [LandingController::class, 'index'])->name('landing');

// rute produk
Route::get('/products', [ProductController::class, 'products'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('product.show');

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

    // rute pembayaran
    Route::get('/payment/{order_id}', [PaymentController::class, 'pay'])->name('payment.pay');

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
    Route::patch('/products/{product}/restock', [AdminProductController::class, 'restock'])->name('products.restock');
    Route::resource('products', AdminProductController::class);
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
    Route::post('/reports/analyze', [ReportController::class, 'analyze'])->name('reports.analyze'); // New Route
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
});

// rute owner (hanya owner yang bisa mengakses)
Route::middleware(['auth', 'role:owner'])->prefix('admin')->name('admin.')->group(function () {

    // Store Settings
    Route::get('store-settings', [StoreSettingController::class, 'edit'])->name('store-settings.edit');
    Route::put('store-settings', [StoreSettingController::class, 'update'])->name('store-settings.update');

    // Admin Management
    Route::resource('admins', AdminController::class);
    Route::post('admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggleStatus');
    Route::post('admins/{admin}/reset-password', [AdminController::class, 'resetPassword'])->name('admins.resetPassword');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Rute callback Midtrans (tanpa auth/csrf untuk menerima notifikasi dari Midtrans)
Route::post('/midtrans/callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

// AI Chatbot Route
Route::post('/ai/chat', [AiChatController::class, 'handleChat'])->name('ai.chat');

require __DIR__ . '/auth.php';

