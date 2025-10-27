<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Filter Status -->
                    <div class="mb-4">
                        <form action="{{ route('admin.transactions.index') }}" method="GET">
                            <label for="status" class="sr-only">Filter by status</label>
                            <select name="status" id="status" onchange="this.form.submit()"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Semua Status</option>
                                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                <option value="diproses" @selected(request('status') == 'diproses')>Diproses</option>
                                <option value="dikirim" @selected(request('status') == 'dikirim')>Dikirim</option>
                                <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                                <option value="failed" @selected(request('status') == 'failed')>Gagal</option>
                            </select>
                        </form>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Order ID</th>
                                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $transaction->order_id }}
                                        </th>
                                        <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
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
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
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
</x-app-layout>
