<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Promo: ') }} <span class="font-mono">{{ $promo->code }}</span>
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
                            <a href="{{ route('admin.promos.index') }}"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600  md:ms-2">Promo</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit Promo</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.promos.update', $promo) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Promo -->
                            <div>
                                <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Kode
                                    Promo:</label>
                                <input type="text" name="code" id="code"
                                    value="{{ old('code', $promo->code) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 uppercase @error('code') border-red-500 @enderror"
                                    required>
                                @error('code')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe Promo -->
                            <div>
                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipe
                                    Promo:</label>
                                <select name="type" id="type"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('type') border-red-500 @enderror"
                                    required>
                                    <option value="fixed" @selected(old('type', $promo->type) == 'fixed')>Potongan Tetap (Rp)</option>
                                    <option value="percentage" @selected(old('type', $promo->type) == 'percentage')>Persentase (%)</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nilai Promo -->
                            <div>
                                <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Nilai
                                    Promo:</label>
                                <input type="number" name="value" id="value"
                                    value="{{ old('value', $promo->value) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('value') border-red-500 @enderror"
                                    required placeholder="Contoh: 10000 atau 10">
                                <p class="text-xs text-gray-500 mt-1">Isi nominal (misal: 10000) jika tipe fixed, atau
                                    persentase (misal: 10) jika tipe percentage.</p>
                                @error('value')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Maksimal Diskon -->
                            <div>
                                <label for="max_discount" class="block text-gray-700 text-sm font-bold mb-2">Maksimal
                                    Diskon (Rp):</label>
                                <input type="number" name="max_discount" id="max_discount"
                                    value="{{ old('max_discount', $promo->max_discount) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('max_discount') border-red-500 @enderror"
                                    placeholder="Kosongkan jika tanpa batas">
                                <p class="text-xs text-gray-500 mt-1">Hanya berlaku untuk tipe persentase.</p>
                                @error('max_discount')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Minimal Pembelian -->
                            <div>
                                <label for="min_purchase" class="block text-gray-700 text-sm font-bold mb-2">Minimal
                                    Pembelian (Rp):</label>
                                <input type="number" name="min_purchase" id="min_purchase"
                                    value="{{ old('min_purchase', $promo->min_purchase) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('min_purchase') border-red-500 @enderror"
                                    placeholder="Kosongkan jika tanpa syarat">
                                @error('min_purchase')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batas Kuota Penggunaan -->
                            <div>
                                <label for="usage_limit" class="block text-gray-700 text-sm font-bold mb-2">Batas Kuota
                                    Penggunaan:</label>
                                <input type="number" name="usage_limit" id="usage_limit"
                                    value="{{ old('usage_limit', $promo->usage_limit) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('usage_limit') border-red-500 @enderror"
                                    placeholder="Kosongkan jika tak terbatas">
                                @error('usage_limit')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal
                                    Mulai:</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $promo->start_date->format('Y-m-d')) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('start_date') border-red-500 @enderror"
                                    required>
                                @error('start_date')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal
                                    Selesai:</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $promo->end_date->format('Y-m-d')) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('end_date') border-red-500 @enderror"
                                    required>
                                @error('end_date')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                                <select name="status" id="status"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('status') border-red-500 @enderror"
                                    required>
                                    <option value="active" @selected(old('status', $promo->status) == 'active')>Aktif</option>
                                    <option value="inactive" @selected(old('status', $promo->status) == 'inactive')>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batasi per Pengguna -->
                            <div class="md:col-span-2 flex items-center">
                                <input id="limit_per_user" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    name="limit_per_user" value="1" @checked(old('limit_per_user', $promo->limit_per_user))>
                                <label for="limit_per_user"
                                    class="ms-2 block text-sm text-gray-900">{{ __('Batasi hanya 1 kali penggunaan per pelanggan') }}</label>
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi
                                    (Opsional):</label>
                                <textarea name="description" id="description" rows="3"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-200 @error('description') border-red-500 @enderror"
                                    placeholder="Deskripsi singkat tentang promo ini...">{{ old('description', $promo->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.promos.index') }}"
                                class="text-sm font-semibold leading-6 text-gray-900 mr-4 px-3">Batal</a>
                            <x-primary-button>
                                Perbarui
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
