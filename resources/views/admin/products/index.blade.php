<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Product') }}
        </h2>
    </x-slot>
    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <!-- Breadcrumb -->
                <nav class="flex " aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 ">
                                Admin
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Product</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500  border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 md:me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden md:inline">Tambah Product</span>
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Kontainer Tabel -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="productsTb" class="min-w-full w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-1">
                                        No.</th>
                                    <th
                                        class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-auto">
                                        Kategori</th>
                                    <th
                                        class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-auto">
                                        Gambar</th>
                                    <th
                                        class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-auto">
                                        Nama</th>
                                    <th
                                        class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-auto">
                                        Harga</th>
                                    <th
                                        class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-auto">
                                        Stock</th>

                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left w-2">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 text-center">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $product->category->name }}</td>
                                        <td class="py-3 px-4">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded">
                                            @else
                                                <span class="text-gray-500">No Image</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $product->name }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $product->stock }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <button type="button" title="Lihat Product"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 show-product-button"
                                                    data-slug="{{ $product->slug }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    title="Edit Product"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product) }}"
                                                    method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus Product"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Product Modal -->
    <x-modal name="show-product-modal" :show="false" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900" x-text="`Detail Produk: ${product.name}`">
            </h2>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-600 mb-2">Gambar Produk:</p>
                    <template x-if="product.image">
                        <img :src="`/storage/${product.image}`" :alt="product.name"
                            class="rounded-md object-cover h-48 w-auto">
                    </template>
                    <template x-if="!product.image">
                        <span class="text-gray-500 text-sm">Tidak ada gambar</span>
                    </template>
                </div>
                <div>
                    <x-input-label value="Nama Produk" />
                    <p class="mt-1 text-sm text-gray-700" x-text="product.name"></p>
                </div>
                <div>
                    <x-input-label value="Kategori" />
                    <p class="mt-1 text-sm text-gray-700" x-text="product.category.name"></p>
                </div>
                <div>
                    <x-input-label value="Harga" />
                    <p class="mt-1 text-sm text-gray-700"
                        x-text="`Rp${new Intl.NumberFormat('id-ID').format(product.price)}`"></p>
                </div>
                <div>
                    <x-input-label value="Stok" />
                    <p class="mt-1 text-sm text-gray-700" x-text="product.stock"></p>
                </div>
                <div class="md:col-span-2">
                    <x-input-label value="Deskripsi" />
                    <p class="mt-1 text-sm text-gray-700" x-text="product.description || '-'"></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Tutup') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
