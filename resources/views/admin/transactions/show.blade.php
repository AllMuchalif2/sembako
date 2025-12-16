<x-app-layout>
    @push('head')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 250px;
                z-index: 0;
            }
        </style>
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pesanan: {{ $transaction->order_id }}
        </h2>
    </x-slot>


    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">

                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 ">
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
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600  md:ms-2">Transaksi</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Detail Transaksi</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <x-secondary-button href="{{ route('admin.transactions.index') }}">
                    <i class="fas fa-arrow-left"></i>
                    <span class="ml-2 hidden md:inline"> Kembali ke Daftar Transaksi</span>
                </x-secondary-button>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Detail & Item -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Detail Item -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pesanan</h3>
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
                                                Rp{{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="border-t border-gray-200 pt-4 mt-4 text-sm">
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Subtotal</dt>
                                    <dd class="font-medium text-gray-900">
                                        Rp{{ number_format($transaction->total_amount + $transaction->discount_amount - ($transaction->shipping_cost ?? 0), 0, ',', '.') }}
                                    </dd>
                                </div>
                                @if ($transaction->promo_code)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600 flex items-center">
                                            <span>Diskon</span>
                                            <span
                                                class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                                {{ $transaction->promo_code }}
                                            </span>
                                        </dt>
                                        <dd class="font-medium text-green-600">
                                            -Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</dd>
                                    </div>
                                @endif
                                @if ($transaction->shipping_cost !== null)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600 flex items-center">
                                            <span>Ongkir</span>
                                            @if ($transaction->shipping_cost == 0)
                                                <span
                                                    class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                                    GRATIS
                                                </span>
                                            @endif
                                        </dt>
                                        <dd class="font-medium text-gray-900">
                                            Rp{{ number_format($transaction->shipping_cost, 0, ',', '.') }}</dd>
                                    </div>
                                @endif
                                <div
                                    class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900">
                                    <dt>Total</dt>
                                    <dd>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengiriman</h3>
                        <div class="space-y-3 text-sm text-gray-700">
                            <p><strong>Alamat Kirim:</strong> {{ $transaction->shipping_address }}</p>
                            @if ($transaction->distance_from_store)
                                <p><strong>Jarak dari Toko:</strong>
                                    {{ number_format($transaction->distance_from_store / 1000, 2) }} km</p>
                            @endif
                            @if ($transaction->shipping_cost !== null)
                                <p>
                                    <strong>Ongkir:</strong>
                                    @if ($transaction->shipping_cost == 0)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                            GRATIS ONGKIR
                                        </span>
                                    @else
                                        Rp{{ number_format($transaction->shipping_cost, 0, ',', '.') }}
                                    @endif
                                </p>
                            @endif
                            @if ($transaction->notes)
                                <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                            @endif
                            <div class="pt-2">
                                <div id="map" class="rounded-lg border"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Info & Aksi -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900  mb-4">Informasi</h3>
                        <div class="space-y-3 text-sm text-gray-700 ">
                            <p><strong>Pelanggan:</strong> {{ $transaction->user->name }}</p>
                            <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                            <p><strong>Nomer HP:</strong> {{ $transaction->user->phone }}</p>
                            <p><strong>Tanggal Pesan:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Metode Pembayaran:</strong>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if ($transaction->payment_method == 'cod') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $transaction->payment_method == 'cod' ? 'COD (Bayar di Tempat)' : 'Midtrans (Online)' }}
                                </span>
                            </p>
                            {{-- <p><strong>Status Pembayaran:</strong> <span
                                    class="font-semibold">{{ ucfirst($transaction->payment_status) }}</span></p> --}}
                            {{-- <p><strong>Metode Pembayaran:</strong>
                                {{ $transaction->payment_type ? ucfirst(str_replace('_', ' ', $transaction->payment_type)) : 'N/A' }}
                            </p> --}}
                        </div>
                    </div>

                    <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900  mb-4">Ubah Status Pesanan</h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600 ">Status saat ini:
                                <span
                                    class="font-bold text-base
                                    @if ($transaction->status == 'diproses') text-blue-600 
                                    @elseif($transaction->status == 'dikirim') text-purple-600 
                                    @else text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </p>

                            @if ($transaction->status == 'pending' && $transaction->payment_method == 'cod')
                                <!-- COD Confirmation Button -->
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Pesanan COD ini menunggu konfirmasi.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('admin.transactions.confirmCod', $transaction) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-primary-button
                                        class="w-full justify-center bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800">
                                        <i class="fas fa-check mr-2"></i>
                                        Konfirmasi Pesanan COD
                                    </x-primary-button>
                                </form>
                            @elseif ($transaction->status == 'diproses')
                                <form action="{{ route('admin.transactions.updateStatus', $transaction) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dikirim">
                                    <x-primary-button class="w-full justify-center">
                                        Ubah ke "Dikirim"
                                    </x-primary-button>
                                </form>
                            @elseif($transaction->status == 'dikirim')
                                @if ($transaction->payment_method == 'cod')
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-blue-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-700">
                                                    Pastikan kurir menerima pembayaran tunai saat pengiriman.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-500 mt-2">Menunggu konfirmasi penerimaan dari
                                    pelanggan.</p>
                            @else
                                <p class="text-sm text-gray-500">Tidak ada aksi yang bisa dilakukan
                                    untuk status ini.</p>
                                @endif @if (in_array($transaction->status, ['diproses', 'dikirim', 'selesai']))
                                    <x-primary-button href="{{ route('admin.transactions.invoice', $transaction) }}"
                                        target="_blank"
                                        class="w-full justify-center mt-2 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800">
                                        Cetak Invoice
                                    </x-primary-button>
                                @endif

                                {{-- Cancel Order Button --}}
                                @if (in_array($transaction->status, ['pending', 'diproses']))
                                    <x-danger-button type="button" onclick="openCancelModal()"
                                        class="w-full justify-center mt-3 text-red-700 bg-red-600 hover:bg-red-50 focus:ring-red-500 border-red-300">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Batalkan Pesanan
                                    </x-danger-button>
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
            // Koordinat toko dari database
            const tokoLat = {{ $settings->store_latitude }};
            const tokoLng = {{ $settings->store_longitude }};
            const freeShippingRadius = {{ $settings->free_shipping_radius }};

            // Koordinat pengiriman
            var deliveryLat = {{ $transaction->latitude ?? 0 }};
            var deliveryLng = {{ $transaction->longitude ?? 0 }};

            // Inisialisasi peta
            var map = L.map('map', {
                dragging: true,
                zoomControl: true,
                scrollWheelZoom: true
            }).setView([deliveryLat, deliveryLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Marker toko (merah)
            var storeIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var storeMarker = L.marker([tokoLat, tokoLng], {
                icon: storeIcon
            }).addTo(map);
            storeMarker.bindPopup("Lokasi Toko");

            // Lingkaran zona gratis ongkir
            var radiusCircle = L.circle([tokoLat, tokoLng], {
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.1,
                radius: freeShippingRadius,
                weight: 2,
                dashArray: '5, 5'
            }).addTo(map);

            radiusCircle.bindPopup(`<b>Zona Gratis Ongkir</b><br>Radius: ${freeShippingRadius/1000} km`);

            // Marker pengiriman (biru)
            var deliveryIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var deliveryMarker = L.marker([deliveryLat, deliveryLng], {
                icon: deliveryIcon
            }).addTo(map);

            // Hitung jarak
            @if ($transaction->distance_from_store)
                var distance = {{ $transaction->distance_from_store }};
                var distanceKm = (distance / 1000).toFixed(2);
                var ongkir = {{ $transaction->shipping_cost ?? 0 }};
                var isInZone = distance <= freeShippingRadius;

                deliveryMarker.bindPopup(
                    `<b><i class='fas fa-map-marker-alt'></i> Lokasi Pengiriman</b><br>` +
                    `Jarak: ${distanceKm} km<br>` +
                    `Ongkir: Rp${ongkir.toLocaleString('id-ID')}` +
                    `${isInZone ? '<br><span style="color: green;"><i class="fas fa-check-circle"></i> Gratis Ongkir!</span>' : ''}`
                ).openPopup();
            @else
                deliveryMarker.bindPopup("<b><i class='fas fa-map-marker-alt'></i> Lokasi Pengiriman</b>").openPopup();
            @endif

            // Fit bounds untuk menampilkan semua marker
            var group = L.featureGroup([storeMarker, deliveryMarker, radiusCircle]);
            map.fitBounds(group.getBounds().pad(0.1));
            map.invalidateSize();
        </script>
    @endpush

    {{-- Cancel Order Modal --}}
    <div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Batalkan Pesanan</h3>
                <form action="{{ route('admin.transactions.cancel', $transaction) }}" method="POST"
                    id="cancelForm">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Pembatalan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            placeholder="Contoh: Stok produk tidak tersedia, Permintaan customer, dll."></textarea>
                        <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                        @error('cancellation_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex gap-3">
                        <x-secondary-button type="button" onclick="closeCancelModal()"
                            class="flex-1 justify-center">
                            Batal
                        </x-secondary-button>
                        <x-danger-button class="flex-1 justify-center">
                            Batalkan Pesanan
                        </x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openCancelModal() {
                document.getElementById('cancelModal').classList.remove('hidden');
            }

            function closeCancelModal() {
                document.getElementById('cancelModal').classList.add('hidden');
                document.getElementById('cancellation_reason').value = '';
            }

            // Close modal when clicking outside
            document.getElementById('cancelModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCancelModal();
                }
            });

            // Show modal if there's validation error
            @if ($errors->has('cancellation_reason'))
                openCancelModal();
            @endif
        </script>
    @endpush
</x-app-layout>
