<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Transaksi') }}
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
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Transaksi</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <!-- Filter Status -->

                <form action="{{ route('admin.transactions.index') }}" method="GET">
                    <label for="status" class="sr-only">Filter by status</label>
                    <select name="status" id="status" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="diproses" @selected(request('status') == 'diproses')>Diproses</option>
                        <option value="dikirim" @selected(request('status') == 'dikirim')>Dikirim</option>
                        <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                        <option value="dibatalkan" @selected(request('status') == 'dibatalkan')>Gagal</option>
                    </select>
                </form>

            </div>
            <!-- Kontainer Tabel -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Order ID</th>
                                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    {{-- <th scope="col" class="px-6 py-3">Status Pembayaran</th> --}}
                                    <th scope="col" class="px-6 py-3">Status Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr class="bg-white border-b  dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer"
                                        title="Klik untuk melihat detail transaksi"
                                        data-href="{{ route('admin.transactions.show', $transaction) }}">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $transaction->order_id }}
                                        </th>
                                        <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </td>
                                        {{-- <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if ($transaction->payment_status == 'settlement') bg-green-100 text-green-800
                                            @elseif($transaction->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($transaction->payment_status) }}
                                            </span>
                                        </td> --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if ($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status == 'diproses') bg-blue-100 text-blue-800
                                            @elseif($transaction->status == 'dikirim') bg-purple-100 text-purple-800
                                            @elseif($transaction->status == 'selesai') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">Tidak ada transaksi ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('tr[data-href]');
                rows.forEach(row => {
                    row.addEventListener('click', () => window.location.href = row.dataset.href);
                });
            });
        </script>
    @endpush
</x-app-layout>
