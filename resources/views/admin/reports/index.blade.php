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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

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

                            {{-- Print Button --}}
                            <div class="md:col-span-3 text-right md:text-right">
                                <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank"
                                    class="inline-flex justify-center items-center px-8 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full shadow-sm">
                                    <i class="fa-solid fa-print mr-2"></i> {{ __('Cetak PDF') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

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
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
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
