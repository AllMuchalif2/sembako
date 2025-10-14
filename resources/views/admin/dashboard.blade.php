<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">{{ __("Selamat Datang Kembali, Admin!") }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Berikut adalah ringkasan aktivitas toko Anda hari ini.") }}
                    </p>
                </div>
            </div>

            <!-- Grid for Stats -->
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat Card 1: Total Pesanan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h4 class="text-sm font-medium text-gray-500">Total Pesanan Baru</h4>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $newOrders }}
                        </p>
                    </div>
                </div>

                <!-- Stat Card 2: Pendapatan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h4>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($todaysRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Stat Card 3: Jumlah Pelanggan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-sm font-medium text-gray-500">Pelanggan Terdaftar</h4>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                             {{ $totalCustomers }}
                        </p>
                    </div>
                </div>

                <!-- Stat Card 4: Stok Menipis -->
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-sm font-medium text-gray-500">Produk Stok Menipis</h4>
                        <p class="mt-1 text-3xl font-semibold text-red-600">
                             {{ $lowStockProducts }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
