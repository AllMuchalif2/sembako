<x-app-layout>
    @push('head')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 250px;
                z-index: 0; /* Pastikan peta tidak menutupi elemen lain seperti modal */
            }
        </style>
    @endpush
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Transaksi: {{ $transaction->order_id }}
            </h2>
            <a href="{{ route('admin.transactions.index') }}"
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                &larr; Kembali ke Daftar Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Detail & Item -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Detail Item -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Item Pesanan</h3>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($transaction->items as $item)
                                <li class="flex py-4">
                                    <div
                                        class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/150' }}"
                                            alt="{{ $item->product_name }}"
                                            class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div
                                                class="flex justify-between text-base font-medium text-gray-900 dark:text-gray-100">
                                                <h3>{{ $item->product_name }}</h3>
                                                <p class="ml-4">Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Rp{{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex justify-between text-base font-medium text-gray-900 dark:text-gray-100">
                                <p>Total</p>
                                <p>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Pengiriman
                        </h3>
                        <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <p><strong>Alamat Kirim:</strong> {{ $transaction->shipping_address }}</p>
                            @if ($transaction->notes)
                                <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                            @endif
                            {{-- Kontainer Peta --}}
                            <div class="pt-2">
                                <div id="map" class="rounded-lg border dark:border-gray-700"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Info & Aksi -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi</h3>
                        <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <p><strong>Pelanggan:</strong> {{ $transaction->user->name }}</p>
                            <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                            <p><strong>Tanggal Pesan:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Status Pembayaran:</strong> <span
                                    class="font-semibold">{{ ucfirst($transaction->payment_status) }}</span></p>
                            <p><strong>Metode Pembayaran:</strong>
                                {{ $transaction->payment_type ? ucfirst(str_replace('_', ' ', $transaction->payment_type)) : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ubah Status Pesanan</h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status saat ini:
                                <span
                                    class="font-bold text-base
                                    @if ($transaction->status == 'diproses') text-blue-600 dark:text-blue-400
                                    @elseif($transaction->status == 'dikirim') text-purple-600 dark:text-purple-400
                                    @else text-gray-800 dark:text-gray-200 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </p>

                            @if ($transaction->status == 'diproses')
                                <form action="{{ route('admin.transactions.updateStatus', $transaction) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dikirim">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Ubah ke "Dikirim"
                                    </button>
                                </form>
                            @elseif($transaction->status == 'dikirim')
                                <form action="{{ route('admin.transactions.updateStatus', $transaction) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Ubah ke "Selesai"
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada aksi yang bisa dilakukan
                                    untuk status ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            var lat = {{ $transaction->latitude ?? 0 }};
            var lng = {{ $transaction->longitude ?? 0 }};

            var map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map).bindPopup("<b>Lokasi Pengiriman</b>").openPopup();
        </script>
    @endpush
</x-app-layout>
