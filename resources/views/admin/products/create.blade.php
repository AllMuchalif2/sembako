<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>
    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
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
                            <a href="{{ route('admin.products.index') }}"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600  md:ms-2">Produk</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Tambah Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Produk -->
                            <div>
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama
                                    Produk:</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="category_id"
                                    class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                                <select name="category_id" id="category_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('category_id') border-red-500 @enderror"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Harga:</label>
                                <div class="flex mt-1 rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">Rp</span>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror" placeholder="10000" required>
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stok -->
                            <div>
                                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stok:</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('stock') border-red-500 @enderror"
                                    required>
                                @error('stock')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-6">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi
                                (Opsional):</label>
                            <textarea name="description" id="description" rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-200 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <div class="mt-6" x-data="{ previewUrl: '' }">
                            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Gambar
                                Produk:</label>
                            <input type="file" name="image" id="image"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('image') border-red-500 @enderror"
                                required @change="previewUrl = URL.createObjectURL($event.target.files[0])">

                            <div x-show="previewUrl" class="mt-4">
                                <p class="text-sm font-medium text-gray-600 mb-2">Pratinjau Gambar:</p>
                                <img :src="previewUrl" alt="Image Preview"
                                    class="rounded-md object-cover h-48 w-auto">
                            </div>

                            @error('image')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.products.index') }}"
                                class="text-sm font-semibold leading-6 text-gray-900 mr-4 px-3">Batal</a>
                            <button type="submit"
                                class="rounded-md bg-blue-500  px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
</x-app-layout>
