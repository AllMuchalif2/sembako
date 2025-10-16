<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        {{-- GANTI SELURUH BLOK x-data LAMA DENGAN INI --}}
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
                                <img :src="`/storage/${product.image}`" :alt="product.name"
                                    class="rounded-lg object-cover w-full aspect-[4/3]">
                            </template>
                            <template x-if="!product.image">
                                <div class="rounded-lg bg-gray-200 w-full aspect-[4/3] flex items-center justify-center">
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
                                <button type="button" class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                                    Tambah ke Keranjang
                                </button>
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



    @stack('scripts')
</body>

</html>
