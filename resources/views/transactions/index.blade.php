<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('transactions.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700">Nama (Order
                                    ID)</label>
                                <input type="text" name="order_id" id="order_id" value="{{ request('order_id') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Cari berdasarkan Order ID">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                    <option value="diproses" @selected(request('status') == 'diproses')>Diproses</option>
                                    <option value="dikirim" @selected(request('status') == 'dikirim')>Dikirim</option>
                                    <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                                    <option value="dibatalkan" @selected(request('status') == 'dibatalkan')>Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Akhir</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Filter</button>

                                <a href="{{ route('transactions.index') }}"
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Reset</a>
                            </div>
                        </div>
                    </form>

                    @if ($transactions->count())
                        <!-- Mobile View (Cards) -->
                        <div class="md:hidden space-y-4">
                            @foreach ($transactions as $transaction)
                                <div class="bg-white p-4 rounded-lg shadow border border-gray-200 cursor-pointer hover:bg-gray-50 transition"
                                    onclick="window.location.href='{{ route('transactions.show', $transaction) }}'">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Order ID</span>
                                            <span
                                                class="font-bold text-gray-900 text-lg">#{{ $transaction->order_id }}</span>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if ($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status == 'diproses') bg-blue-100 text-blue-800
                                            @elseif($transaction->status == 'dikirim') bg-purple-100 text-purple-800
                                            @elseif($transaction->status == 'selesai') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center border-t pt-3">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Tanggal</span>
                                            <span
                                                class="text-sm text-gray-700">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-500 block">Total</span>
                                            <span
                                                class="font-bold text-blue-600 text-lg">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop View (Table) -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="transactionsTb">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status Transaksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transactions as $transaction)
                                        <tr class="hover:bg-gray-100 cursor-pointer"
                                            data-href="{{ route('transactions.show', $transaction) }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $transaction->order_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if ($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($transaction->status == 'diproses') bg-blue-100 text-blue-800
                                                    @elseif($transaction->status == 'dikirim') bg-purple-100 text-purple-800
                                                    @elseif($transaction->status == 'selesai') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Tidak ada riwayat transaksi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('tr[data-href]');

                rows.forEach(row => {
                    row.addEventListener('click', () => {
                        window.location.href = row.dataset.href;
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
