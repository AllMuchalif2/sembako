<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Admin
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-gray-400 mx-1 text-xs"></i>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Laporan Penjualan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- Filter Form --}}
                <form action="{{ route('admin.product-reports.index') }}" method="GET" class="mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            {{-- Date Inputs --}}
                            <div class="md:col-span-3">
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700">{{ __('Tanggal Mulai') }}</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                            </div>
                            <div class="md:col-span-3">
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700">{{ __('Tanggal Selesai') }}</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                            </div>

                            {{-- Filter Buttons --}}
                            <div class="md:col-span-6 flex space-x-2">
                                <x-primary-button type="submit" class="justify-center w-full md:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                <x-secondary-button as="a" href="{{ route('admin.product-reports.index') }}"
                                    class="justify-center w-full md:w-auto">
                                    {{ __('Reset') }}
                                </x-secondary-button>
                                <a href="{{ route('admin.product-reports.print', request()->all()) }}" target="_blank"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fa-solid fa-print mr-2"></i> {{ __('Cetak Laporan') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Summary Statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('admin.transactions.index', array_filter(['start_date' => request('start_date'), 'end_date' => request('end_date')])) }}"
                        class="block p-4 bg-green-50 rounded-lg border border-green-100 hover:bg-green-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-green-700 mt-1">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </p>
                            </div>
                            <i class="fa-solid fa-sack-dollar text-3xl text-green-300"></i>
                        </div>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Lihat transaksi
                        </p>
                    </a>

                    <a href="{{ route('admin.transactions.index', array_filter(['start_date' => request('start_date'), 'end_date' => request('end_date')])) }}"
                        class="block p-4 bg-purple-50 rounded-lg border border-purple-100 hover:bg-purple-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600">Total Keuntungan</p>
                                <p class="text-2xl font-bold text-purple-700 mt-1">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </p>
                            </div>
                            <i class="fa-solid fa-chart-line text-3xl text-purple-300"></i>
                        </div>
                        <p class="text-xs text-purple-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Lihat transaksi
                        </p>
                    </a>

                    <a href="{{ route('admin.transactions.index', array_filter(['start_date' => request('start_date'), 'end_date' => request('end_date')])) }}"
                        class="block p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Terjual</p>
                                <p class="text-2xl font-bold text-blue-700 mt-1">
                                    {{ number_format($totalProductsSold) }} Unit
                                </p>
                            </div>
                            <i class="fa-solid fa-shopping-cart text-3xl text-blue-300"></i>
                        </div>
                        <p class="text-xs text-blue-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Lihat transaksi
                        </p>
                    </a>
                </div>



                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terjual
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendapatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keuntungan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($productReports as $report)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $report['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['category'] }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">
                                        {{ number_format($report['total_sold']) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-green-700 text-right font-semibold">
                                        Rp {{ number_format($report['total_revenue'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-purple-700 text-right font-semibold">
                                        Rp {{ number_format($report['total_profit'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data produk ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Total
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    {{ number_format($totalProductsSold) }}
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-green-700 uppercase tracking-wider">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-purple-700 uppercase tracking-wider">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
