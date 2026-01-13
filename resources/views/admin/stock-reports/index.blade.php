<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Stok') }}
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
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Laporan Stok</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div x-data="{
                loading: false,
                analysisResult: '',
                analyze() {
                    this.loading = true;
                    this.analysisResult = '';
            
                    const urlParams = new URLSearchParams(window.location.search);
            
                    fetch('{{ route('admin.stock-reports.analyze') }}?' + urlParams.toString(), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.analysisResult = data.analysis;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.analysisResult = 'Terjadi kesalahan saat melakukan analisa.';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- Filter Form --}}
                <form action="{{ route('admin.stock-reports.index') }}" method="GET" class="mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            {{-- Category Filter --}}
                            <div class="md:col-span-3">
                                <label for="category_id"
                                    class="block text-sm font-medium text-gray-700">{{ __('Kategori') }}</label>
                                <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="md:col-span-3">
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700">{{ __('Status Stok') }}</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua
                                        Status
                                    </option>
                                    <option value="safe" {{ request('status') == 'safe' ? 'selected' : '' }}>Aman
                                    </option>
                                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Rendah
                                    </option>
                                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Habis
                                    </option>
                                </select>
                            </div>

                            {{-- Filter Buttons --}}
                            <div class="md:col-span-3 flex space-x-2">
                                <x-primary-button type="submit" class="justify-center w-full md:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                <x-secondary-button as="a" href="{{ route('admin.stock-reports.index') }}"
                                    class="justify-center w-full md:w-auto">
                                    {{ __('Reset') }}
                                </x-secondary-button>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="md:col-span-3 text-right md:text-right flex flex-col space-y-2">
                                <a href="{{ route('admin.stock-reports.print', request()->all()) }}" target="_blank"
                                    class="inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition w-full shadow-sm">
                                    <i class="fa-solid fa-print mr-2"></i> {{ __('Cetak PDF') }}
                                </a>

                                <button type="button" @click="analyze" :disabled="loading"
                                    class="inline-flex justify-center items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition w-full shadow-sm disabled:opacity-50">
                                    <template x-if="!loading">
                                        <span><i class="fa-solid fa-robot mr-2"></i> {{ __('Analisa AI') }}</span>
                                    </template>
                                    <template x-if="loading">
                                        <span><i class="fa-solid fa-spinner fa-spin mr-2"></i> Analisa...</span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- AI Result Section --}}
                <div x-show="analysisResult" x-transition
                    class="mb-6 p-6 bg-purple-50 rounded-lg border border-purple-100 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-wand-magic-sparkles text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-purple-900">Analisa Cerdas AI</h3>
                            <div class="mt-2 text-purple-800 text-sm whitespace-pre-line leading-relaxed"
                                x-text="analysisResult"></div>
                        </div>
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <a href="{{ route('admin.products.index') }}"
                        class="block p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Produk</p>
                                <p class="text-2xl font-bold text-blue-700 mt-1">
                                    {{ number_format($totalProducts) }}
                                </p>
                            </div>
                            <i class="fa-solid fa-box text-3xl text-blue-300"></i>
                        </div>
                        <p class="text-xs text-blue-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Lihat produk
                        </p>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="block p-4 bg-green-50 rounded-lg border border-green-100 hover:bg-green-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600">Total Nilai Stok</p>
                                <p class="text-2xl font-bold text-green-700 mt-1">
                                    Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                                </p>
                            </div>
                            <i class="fa-solid fa-sack-dollar text-3xl text-green-300"></i>
                        </div>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Lihat produk
                        </p>
                    </a>

                    <a href="{{ route('admin.stock-reports.index', ['status' => 'low'] + request()->only(['category_id'])) }}"
                        class="block p-4 bg-yellow-50 rounded-lg border border-yellow-100 hover:bg-yellow-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Stok Rendah</p>
                                <p class="text-2xl font-bold text-yellow-700 mt-1">
                                    {{ number_format($lowStockCount) }}
                                </p>
                            </div>
                            <i class="fa-solid fa-triangle-exclamation text-3xl text-yellow-300"></i>
                        </div>
                        <p class="text-xs text-yellow-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Filter stok rendah
                        </p>
                    </a>

                    <a href="{{ route('admin.stock-reports.index', ['status' => 'out'] + request()->only(['category_id'])) }}"
                        class="block p-4 bg-red-50 rounded-lg border border-red-100 hover:bg-red-100 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-600">Stok Habis</p>
                                <p class="text-2xl font-bold text-red-700 mt-1">
                                    {{ number_format($outOfStockCount) }}
                                </p>
                            </div>
                            <i class="fa-solid fa-circle-xmark text-3xl text-red-300"></i>
                        </div>
                        <p class="text-xs text-red-600 mt-2 flex items-center">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Filter stok habis
                        </p>
                    </a>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table id="stockTable" class="min-w-full divide-y divide-gray-200">
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
                                    Stok
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga Beli
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai Stok
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Terjual
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stockReports as $report)
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
                                        {{ number_format($report['stock']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($report['status_color'] == 'green')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $report['status_label'] }}
                                            </span>
                                        @elseif($report['status_color'] == 'yellow')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $report['status_label'] }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $report['status_label'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                        Rp {{ number_format($report['buy_price'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-green-700 text-right font-semibold">
                                        Rp {{ number_format($report['stock_value'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 text-right font-semibold">
                                        {{ number_format($report['total_sold']) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data produk ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
