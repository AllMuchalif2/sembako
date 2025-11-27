<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- <!-- Welcome Banner -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Selamat Datang Kembali, Admin!') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Berikut adalah ringkasan aktivitas toko Anda hari ini.') }}
                    </p>
                </div>
            </div> --}}

            <!-- Grid for Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stat Card 1: Total Pesanan -->
                <a href="{{ route('admin.transactions.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-blue-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-box-open text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Pesanan Baru</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $newOrders }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 2: Pendapatan -->
                <a href="{{ route('admin.transactions.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-green-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        Rp {{ number_format($todaysRevenue, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 3: Jumlah Pelanggan -->
                <a href="#"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg cursor-default">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-yellow-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-users text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Pelanggan Terdaftar</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $totalCustomers }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 4: Stok Menipis -->
                <a href="{{ route('admin.products.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-red-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Produk Stok Menipis</h4>
                                    <p class="mt-1 text-2xl font-semibold text-red-600">
                                        {{ $lowStockProducts }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 5: Total Produk -->
                <a href="{{ route('admin.products.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-indigo-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Produk</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $totalProducts }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 6: Promo Aktif -->
                <a href="{{ route('admin.promos.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-pink-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-tags text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Promo Aktif</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $activePromos }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 7: Transaksi Selesai -->
                <a href="{{ route('admin.transactions.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-teal-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-check-circle text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Transaksi Selesai</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $completedTransactions }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 8: Total Pendapatan -->
                <a href="{{ route('admin.transactions.index') }}"
                    class="block transform transition duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-purple-500 rounded-md h-12 w-12 flex items-center justify-center">
                                    <i class="fa-solid fa-wallet text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Grid for Recent Transactions and Low Stock Products -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Transaksi Terbaru</h3>
                        <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200">
                                @forelse ($recentTransactions as $transaction)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $transaction->order_id }}</p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $transaction->user->name }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold">
                                                    Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($transaction->status) }}</span>
                                            </div>
                                            <a href="{{ route('admin.transactions.show', $transaction) }}"
                                                class="text-blue-600 hover:text-blue-800">&rarr;</a>
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-gray-500">Tidak ada transaksi terbaru.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Produk Stok Menipis</h3>
                        <div class="mt-4 flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200">
                                @forelse ($lowStockProductsList as $product)
                                    <li class="py-3">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $product->name }}</p>
                                            </div>
                                            <div>
                                                <span class="text-sm font-bold text-red-600">Sisa:
                                                    {{ $product->stock }}</span>
                                            </div>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="text-blue-600 hover:text-blue-800">&rarr;</a>
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-gray-500">Tidak ada produk dengan stok menipis.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
