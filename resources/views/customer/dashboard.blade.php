<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Akun Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Kolom Kiri: Informasi Akun & Pintasan -->
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Alamat Utama:</strong> {{ $primaryAddress }}</p>
                        </div>
                        <div class="mt-6 space-y-2">
                            <a href="{{ route('profile.edit') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ubah Profil
                            </a>
                            {{-- <a href="{{ route('password.edit') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ubah Password
                            </a> --}}
                            <a href="{{ route('transactions.index') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Riwayat Transaksi Lengkap
                            </a>
                            
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan Pesanan Terbaru -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan Terbaru</h3>
                        @if ($latestTransactions->count() > 0)
                            <div class="space-y-4">
                                @foreach ($latestTransactions as $transaction)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex justify-between items-center mb-2">
                                            <p class="text-sm font-medium text-gray-800">Order ID: <span class="font-semibold">{{ $transaction->order_id }}</span></p>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($transaction->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($transaction->status == 'success') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</p>
                                        <p class="text-sm text-gray-600">Total: <span class="font-semibold">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span></p>
                                        <div class="mt-3 text-right">
                                            {{-- Asumsi ada route untuk detail transaksi --}}
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Detail &rarr;</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">Anda belum memiliki riwayat pesanan.</p>
                            <div class="mt-4">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Mulai Belanja
                                </a>
                            </div>
                        @endif

                        {{-- Placeholder untuk Produk Rekomendasi (bisa diimplementasikan nanti) --}}
                        {{-- <h3 class="text-lg font-semibold text-gray-900 mt-8 mb-4">Produk Rekomendasi untuk Anda</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <p class="font-medium">Produk A</p>
                                <p class="text-sm text-gray-600">Deskripsi singkat produk A.</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <p class="font-medium">Produk B</p>
                                <p class="text-sm text-gray-600">Deskripsi singkat produk B.</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
