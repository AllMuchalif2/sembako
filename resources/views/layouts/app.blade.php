<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $routeName = Route::currentRouteName() ?? '';
        
        // Mapping route patterns to Indonesian titles
        $titleMap = [
            'admin.dashboard' => 'Dashboard',
            'admin.categories' => 'Kategori',
            'admin.products' => 'Produk',
            'admin.promos' => 'Promo',
            'admin.transactions' => 'Transaksi',
            'admin.admins' => 'Admin',
            'admin.store-settings' => 'Toko',
            'admin.profile' => 'Profil',
            'customer.dashboard' => 'Dashboard',
            'profile' => 'Profil',
            'cart' => 'Keranjang',
            'checkout' => 'Checkout',
        ];

        $pageTitle = 'Halaman'; // Default
        
        // Check for exact match first
        if (isset($titleMap[$routeName])) {
            $pageTitle = $titleMap[$routeName];
        } else {
            // Check for prefix match (e.g. admin.products.index matches admin.products)
            foreach ($titleMap as $key => $value) {
                if (str_starts_with($routeName, $key)) {
                    $pageTitle = $value;
                    break;
                }
            }
            // Fallback if no map found: use the last segment of the route name
            if (!isset($titleMap[$routeName]) && $pageTitle === 'Halaman') {
                 $segments = explode('.', $routeName);
                 $pageTitle = ucwords(str_replace(['-', '_'], ' ', end($segments)));
            }
        }

        $fullTitle = $pageTitle;
        if (request()->routeIs('admin.*')) {
            $fullTitle .= ' | Admin MyMart';
        } else {
            $fullTitle .= ' | My Mart';
        }
    @endphp
    <title>{{ $fullTitle }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="icon" href="{{ secure_asset('images/logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">
    {{-- Logika utama: Cek apakah ini halaman admin. --}}
    @if (request()->routeIs('admin.*'))
        {{-- Layout Admin dengan Sidebar Kustom --}}
        <div x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            product: { name: '', description: '', price: 0, stock: 0, image: '', category: { name: '' } },
            category: { name: '', description: '' },
        
            init() {
                // Event listener untuk tombol 'show product'
                document.addEventListener('click', (event) => {
                    const button = event.target.closest('.show-product-button');
                    if (button) {
                        const productSlug = button.dataset.slug;
                        fetch(`/admin/products/${productSlug}`)
                            .then(response => response.json())
                            .then(data => {
                                this.product = data;
                                this.$dispatch('open-modal', 'show-product-modal');
                            });
                    }
                });
        
                // Event listener untuk tombol 'show category'
                document.addEventListener('click', (event) => {
                    const button = event.target.closest('.show-category-button');
                    if (button) {
                        const categoryId = button.dataset.id;
                        fetch(`/admin/categories/${categoryId}`)
                            .then(response => response.json())
                            .then(data => {
                                this.category = data;
                                this.$dispatch('open-modal', 'show-category-modal');
                            });
                    }
                });
        
                this.$watch('sidebarOpen', value => {
                    if (value && window.innerWidth < 1024) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            }
        }"
            @resize.window="if (window.innerWidth >= 1024) { sidebarOpen = true; document.body.style.overflow = ''; }"
            class="flex min-h-screen">

            @include('layouts.admin')

            <div class="flex-1 flex flex-col" x-data>

                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center gap-4">
                            <button @click="sidebarOpen = !sidebarOpen" type="button"
                                class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors lg:hidden">
                                <i class="fa-solid fa-bars text-xl"></i>
                            </button>

                            <div class="flex-1 text-gray-800">
                                {{ $header }}
                            </div>
                        </div>
                    </header>
                @endif

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        {{-- Layout Standar untuk Customer --}}
        <div class="min-h-screen bg-gray-100" x-data="{
            product: { name: '', description: '', price: 0, stock: 0, image: '', category: { name: '' } },
            init() {
                document.addEventListener('click', (event) => {
                    const button = event.target.closest('.show-product-modal-button');
                    if (button) {
                        event.preventDefault();
                        const productSlug = button.dataset.slug;
                        fetch(`/products/${productSlug}`)
                            .then(response => response.json())
                            .then(data => {
                                this.product = data;
                                this.$dispatch('open-modal', 'show-product-detail-modal');
                            });
                    }
                });
            }
        }">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>

            <!-- Modal Detail Produk untuk Customer -->
            <x-modal name="show-product-detail-modal" :show="false" maxWidth="2xl" focusable>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kolom Gambar -->
                        <div>
                            <template x-if="product.image">
                                <div class="aspect-square w-full bg-white rounded-lg flex items-center justify-center overflow-hidden border">
                                    <img :src="`/storage/${product.image}`" :alt="product.name"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                            </template>
                            <template x-if="!product.image">
                                <div class="rounded-lg bg-gray-200 w-full aspect-square flex items-center justify-center">
                                    <span class="text-gray-500">Tidak ada gambar</span>
                                </div>
                            </template>
                        </div>

                        <!-- Kolom Info Produk -->
                        <div class="flex flex-col">
                            <h2 class="text-2xl font-bold text-gray-900" x-text="product.name"></h2>
                            <p class="text-sm text-gray-500 mt-1" x-text="product.category.name"></p>

                            <p class="text-3xl font-bold text-gray-900 mt-4" x-text="`Rp${new Intl.NumberFormat('id-ID').format(product.price)}`"></p>
                            <p class="text-sm text-gray-600 mt-2" x-text="`Stok: ${product.stock}`"></p>

                            <div class="mt-6 prose max-w-none text-gray-700">
                                <p x-text="product.description || 'Tidak ada deskripsi untuk produk ini.'"></p>
                            </div>

                            <div class="mt-auto pt-6">
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" :value="product.id">
                                    <input type="hidden" name="quantity" value="1"> <!-- Default quantity 1, bisa dikembangkan -->
                                    <button type="submit"
                                        class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition"
                                        :disabled="product.stock <= 0"
                                        :class="{ 'opacity-50 cursor-not-allowed': product.stock <= 0 }">
                                        <span x-text="product.stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis'"></span>
                                    </button>
                                </form>
                                <x-secondary-button class="w-full justify-center mt-2" x-on:click="$dispatch('close')">
                                    Tutup
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-modal>
        </div>
    @endif

    <!-- Global Notification -->
    @if (session('success') || session('warning') || session('error'))
        <div x-data="{ 
                show: true, 
                progress: 100,
                duration: 5000,
                interval: null,
                startTimer() {
                    const step = 100 / (this.duration / 50);
                    this.interval = setInterval(() => {
                        this.progress -= step;
                        if (this.progress <= 0) {
                            clearInterval(this.interval);
                            this.show = false;
                        }
                    }, 50);
                },
                close() {
                    clearInterval(this.interval);
                    this.show = false;
                }
            }" 
            x-init="startTimer()" 
            x-show="show" 
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100" 
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-5 sm:bottom-5 z-50 sm:max-w-sm w-auto sm:w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
            
            <!-- Progress bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r 
                @if (session('success')) from-green-400 to-green-600
                @elseif (session('warning')) from-yellow-400 to-yellow-600
                @else from-red-400 to-red-600
                @endif
                transition-all duration-50 ease-linear"
                :style="'width: ' + progress + '%'">
            </div>

            <div class="p-3 sm:p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if (session('success')) <i class="fa-solid fa-circle-check text-green-500 text-lg sm:text-xl"></i> @endif
                        @if (session('warning')) <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-lg sm:text-xl"></i> @endif
                        @if (session('error')) <i class="fa-solid fa-circle-xmark text-red-500 text-lg sm:text-xl"></i> @endif
                    </div>
                    <div class="ml-2 sm:ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-xs sm:text-sm font-medium text-gray-900">{{ session('success') ?? session('warning') ?? session('error') }}</p>
                    </div>
                    <div class="ml-2 flex-shrink-0 flex">
                        <button @click="close()" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-md p-1">
                            <span class="sr-only">Close</span>
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @stack('scripts')
</body>

</html>
