<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Grid for 4 Main Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Total Pendapatan -->
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

                <!-- Card 2: Pendapatan Hari Ini -->
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

                <!-- Card 3: Total Pesanan Baru -->
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

                <!-- Card 4: Stok Menipis -->
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
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Chart 1: Pendapatan 7 Hari Terakhir -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pendapatan 7 Hari Terakhir</h3>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Top 5 Produk Terlaris -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top 5 Produk Terlaris</h3>
                        <canvas id="topProductsChart" height="100"></canvas>
                    </div>
                </div>
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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // Initialize all charts when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Revenue Chart (Line)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($revenueData),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });

            // Chart 2: Top Products Chart (Bar)
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: @json($topProductLabels),
                    datasets: [{
                        label: 'Terjual',
                        data: @json($topProductData),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(251, 191, 36)',
                            'rgb(239, 68, 68)',
                            'rgb(139, 92, 246)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
