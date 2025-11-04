<x-app-layout>
    @push('head')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 400px;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Kolom Kiri: Detail Pengiriman & Peta -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Pengiriman</h2>

                        <!-- Alamat Pengiriman -->
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Alamat
                                Lengkap</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Contoh: Jl. Pahlawan No. 123, Kel. Suka Maju, Kec. Damai" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Peta Leaflet -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Tandai Lokasi di Peta</label>
                            <div id="map" class="mt-2 rounded-lg border border-gray-300"></div>
                            <p class="text-xs text-gray-500 mt-1">Klik pada peta untuk menentukan titik lokasi pengiriman.
                            </p>
                        </div>

                        <!-- Input Latitude & Longitude (Hidden) -->
                        <input type="hidden" name="latitude" id="latitude" required>
                        <input type="hidden" name="longitude" id="longitude" required>
                        @error('latitude')
                            <p class="text-red-500 text-xs mt-1">Harap tandai lokasi di peta.</p>
                        @enderror

                        <!-- Catatan (Opsional) -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan untuk
                                Penjual (Opsional)</label>
                            <textarea id="notes" name="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Contoh: Pagar warna hitam, titip di satpam.">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan Pesanan -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Pesanan</h2>
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach ($cartItems as $item)
                                    <li class="flex py-4">
                                        <div
                                            class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/150' }}"
                                                alt="{{ $item['name'] }}"
                                                class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div
                                                    class="flex justify-between text-sm font-medium text-gray-900">
                                                    <h3>{{ $item['name'] }}</h3>
                                                    <p class="ml-4">
                                                        Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">Qty: {{ $item['quantity'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Subtotal</p>
                                    <p>Rp{{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">Pajak dan ongkir akan dihitung nanti.</p>
                                <div class="mt-6">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700">
                                        Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            var map;
            var marker;
            var defaultLat = -6.873167464166432;
            var defaultLng = 109.66582626527774;
            var defaultZoom = 13;

            // Cek apakah ada nilai latitude/longitude dari old() (misal setelah validasi gagal)
            var initialLat = {{ old('latitude', 'null') }} || defaultLat;
            var initialLng = {{ old('longitude', 'null') }} || defaultLng;

            // Inisialisasi peta
            map = L.map('map').setView([initialLat, initialLng], defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Fungsi untuk menambahkan atau memindahkan marker
            function placeMarker(lat, lng) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lng]).addTo(map);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            // Jika ada nilai old() atau default, langsung tempatkan marker
            if (initialLat !== null && initialLng !== null) {
                placeMarker(initialLat, initialLng);
            }

            // Coba dapatkan lokasi pengguna saat ini
            if (navigator.geolocation && ({{ old('latitude', 'null') }} === null || {{ old('longitude', 'null') }} === null)) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    map.setView([userLat, userLng], defaultZoom);
                    placeMarker(userLat, userLng);
                }, function(error) {
                    console.warn('ERROR(' + error.code + '): ' + error.message);
                    // Jika gagal, gunakan default atau old() yang sudah diatur
                    map.setView([initialLat, initialLng], defaultZoom);
                    if (!marker) { // Hanya tambahkan jika belum ada marker dari old()
                        placeMarker(initialLat, initialLng);
                    }
                });
            } else if (!marker) {
                // Jika geolocation tidak didukung atau sudah ada old() dan tidak ada marker, gunakan default
                placeMarker(initialLat, initialLng);
            }

            // Event listener untuk klik peta
            function onMapClick(e) {
                placeMarker(e.latlng.lat, e.latlng.lng);
            }

            map.on('click', onMapClick);

            // Pastikan peta di-render ulang dengan benar jika ada masalah tampilan
            map.invalidateSize();
        </script>
    @endpush
</x-app-layout>
