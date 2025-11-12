<x-app-layout>
    @push('head')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 300px;
                z-index: 0;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi: ') . $transaction->order_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Kolom Detail Transaksi -->
                    <div class="md:col-span-1 space-y-4">
                        <h3 class="text-lg font-semibold border-b pb-2">Detail Pesanan</h3>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $transaction->order_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                        
                        {{-- <div>
                            <dt class="text-sm font-medium text-gray-500">Status Pembayaran</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($transaction->payment_status == 'settlement') bg-green-100 text-green-800
                                    @elseif($transaction->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </dd>
                        </div> --}}
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Transaksi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status == 'diproses') bg-blue-100 text-blue-800
                                    @elseif($transaction->status == 'dikirim') bg-purple-100 text-purple-800
                                    @elseif($transaction->status == 'selesai') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Pembayaran</dt>
                            <dd class="mt-1 text-lg font-bold text-gray-900">
                                Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $transaction->shipping_address }}</dd>
                        </div>
                        @if ($transaction->notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $transaction->notes }}</dd>
                            </div>
                        @endif

                        <div class="mt-4">
                            <div id="map" class="rounded-lg"></div>
                        </div>
                    </div>

                    <!-- Kolom Item yang Dipesan -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Item yang Dipesan</h3>
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach ($transaction->items as $item)
                                <li class="flex py-4">
                                    <div
                                        class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/150' }}"
                                            alt="{{ $item->product_name }}"
                                            class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item->product_name }}</h3>
                                                <p class="ml-4">Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $item->quantity }} x
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Aksi Pelanggan --}}
                        @if ($transaction->status == 'dikirim')
                            <div class="mt-6 border-t pt-4">
                                <p class="text-sm text-gray-600 mb-2">Apakah pesanan Anda sudah sampai? Klik tombol di
                                    bawah
                                    ini untuk menyelesaikan transaksi.</p>
                                <form action="{{ route('transactions.complete', $transaction) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Pesanan Diterima
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Aksi Pembatalan oleh Pelanggan --}}
                        @if ($transaction->status == 'pending' || $transaction->status == 'diproses')
                            <div class="mt-6 border-t pt-4">
                                <p class="text-sm text-gray-600 mb-2">Ingin membatalkan pesanan ini?</p>
                                <form action="{{ route('transactions.cancel', $transaction) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @endif
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
            var lat = {{ $transaction->latitude }};
            var lng = {{ $transaction->longitude }};

            var map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup("<b>Lokasi Pengiriman</b>").openPopup();
        </script>
    @endpush
</x-app-layout>
