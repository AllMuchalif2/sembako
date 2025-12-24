<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Transaksi') }}
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
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Laporan Transaksi</span>
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
            
                    // Ambil parameter dari URL untuk filter saat ini
                    const urlParams = new URLSearchParams(window.location.search);
            
                    fetch('{{ route('admin.reports.analyze') }}?' + urlParams.toString(), {
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
                <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-8">
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
                            <div class="md:col-span-3 flex space-x-2">
                                <x-primary-button type="submit" class="justify-center w-full md:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                <x-secondary-button as="a" href="{{ route('admin.reports.index') }}"
                                    class="justify-center w-full md:w-auto">
                                    {{ __('Reset') }}
                                </x-secondary-button>
                            </div>


                            {{-- Action Buttons --}}
                            <div class="md:col-span-3 text-right md:text-right flex flex-col space-y-2">
                                <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank"
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

                {{-- Total Revenue --}}
                <div
                    class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex justify-between items-center">
                    <div>
                        <span class="text-indigo-800 font-semibold">Total Pendapatan (Selesai):</span>
                        <p class="text-sm text-indigo-600 mt-1">
                            @if (request('start_date') && request('end_date'))
                                Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                            @elseif(request('start_date'))
                                Sejak: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                            @elseif(request('end_date'))
                                Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                            @else
                                Semua Waktu
                            @endif
                        </p>
                    </div>
                    <div class="text-2xl font-bold text-indigo-700">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Total Profit & Margin --}}

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">

                        <span class="text-green-800 font-semibold">Total Keuntungan:</span>

                        <p class="text-2xl font-bold text-green-700 mt-2">

                            Rp {{ number_format($totalProfit, 0, ',', '.') }}

                        </p>

                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">

                        <span class="text-blue-800 font-semibold">Margin Keuntungan:</span>

                        <p class="text-2xl font-bold text-blue-700 mt-2">

                            {{ number_format($marginPercentage, 1) }}%

                        </p>

                    </div>

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
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Pesanan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keuntungan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Margin
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $transaction->order_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->user->name ?? 'User Terhapus' }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-green-700 text-right font-semibold">
                                        @php
                                            $transactionProfit = 0;
                                            foreach ($transaction->items as $item) {
                                                if ($item->product && $item->product->buy_price) {
                                                    $transactionProfit +=
                                                        ($item->price - $item->product->buy_price) * $item->quantity;
                                                }
                                            }
                                        @endphp
                                        Rp {{ number_format($transactionProfit, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 text-right font-semibold">
                                        @php
                                            $transactionCost = $transaction->total_amount - $transactionProfit;
                                            $transactionMargin =
                                                $transactionCost > 0
                                                    ? ($transactionProfit / $transactionCost) * 100
                                                    : 0;
                                        @endphp
                                        {{ number_format($transactionMargin, 1) }}%
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada transaksi selesai ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Total
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-green-700 uppercase tracking-wider">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-blue-700 uppercase tracking-wider">
                                    {{ number_format($marginPercentage, 1) }}%
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-900 uppercase tracking-wider">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
