<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Kolom Filter Kategori - Sticky Sidebar -->
                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-24">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategori</h2>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('products.index', ['search' => request('search')]) }}"
                                        class="block px-4 py-2 rounded-md text-sm font-medium {{ !request('category') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-200' }}">
                                        Semua Kategori
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('products.index', ['category' => $category->slug, 'search' => request('search')]) }}"
                                            class="flex justify-between items-center px-4 py-2 rounded-md text-sm font-medium {{ request('category') == $category->slug ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-200' }}">
                                            <span>{{ $category->name }}</span>
                                            <span
                                                class="text-xs font-semibold {{ request('category') == $category->slug ? 'text-blue-600' : 'text-gray-500' }}">{{ $category->products_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>

                    <!-- Kolom Produk -->
                    <div class="lg:col-span-3">
                        <!-- Search Bar & Sorting -->
                        <form action="{{ route('products.index') }}" method="GET" id="filter-form"
                            class="lg:mb-8 sticky top-1 lg:top-20 lg:static z-20 bg-gray-100 -mx-4 sm:-mx-6 lg:mx-0 px-4 sm:px-6 lg:px-0 py-4 lg:py-0 shadow-sm lg:shadow-none">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="search" name="search"
                                    class="block w-full rounded-md border-0 bg-white py-3 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6"
                                    placeholder="Cari di kategori ini..." type="search"
                                    value="{{ request('search') }}">
                            </div>
                        </form>

                        <!-- Grid Produk -->
                        @if ($products->count())
                            <div
                                class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
                                @foreach ($products as $product)
                                    <div class="group relative">
                                        <div
                                            class="flex flex-col h-full bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 ease-in-out group-hover:shadow-xl group-hover:-translate-y-1">
                                            <div
                                                class="aspect-square w-full bg-white flex items-center justify-center overflow-hidden">
                                                <div
                                                    class="aspect-square w-full bg-white flex items-center justify-center overflow-hidden">
                                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                                                        alt="{{ $product->name }}"
                                                        class="h-full w-full object-contain object-center transition-opacity duration-300 group-hover:opacity-75">
                                                </div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="text-sm font-medium text-gray-800">
                                                    <a href="{{ route('product.show', $product) }}"
                                                        class="show-product-modal-button"
                                                        data-slug="{{ $product->slug }}">
                                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                                        {{ \Illuminate\Support\Str::limit($product->name, 45) }}
                                                    </a>
                                                </h3>
                                                <p class="mt-1 text-xs text-gray-500">{{ $product->category->name }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">Stock: {{ $product->stock }}
                                                </p>
                                                <div class="mt-auto pt-4">
                                                    <p class="text-base font-bold text-blue-500">
                                                    <div class="flex justify-between items-center">
                                                        <p class="text-base font-bold text-blue-500">
                                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                                        </p>
                                                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form relative z-10"
                                                            >
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->id }}">
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit"
                                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                                @if ($product->stock <= 0) disabled title="Stok habis" @else title="Tambah ke Keranjang" @endif>
                                                                <i class="fa-solid fa-cart-plus text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Paginasi -->
                            <div class="mt-12">
                                {{ $products->links() }}
                            </div>
                        @else
                            <div class="text-center py-16 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">Produk tidak ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Coba ubah kata kunci pencarian atau filter kategori Anda.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>