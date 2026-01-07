<x-app-layout>
    @push('head')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #store-map {
                height: 400px;
                border-radius: 0.5rem;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
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
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Pengaturan Toko</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <form method="POST" action="{{ route('admin.store-settings.update') }}" class="space-y-6"
                        id="store-settings-form">
                        @csrf
                        @method('PUT')

                        <!-- Store Name -->
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">
                                Nama Toko
                            </label>
                            <input type="text" name="store_name" id="store_name"
                                value="{{ old('store_name', $settings->store_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('store_name') border-red-500 @enderror"
                                required>
                            @error('store_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Store Address -->
                        <div>
                            <label for="store_address" class="block text-sm font-medium text-gray-700">
                                Alamat Toko
                            </label>
                            <textarea name="store_address" id="store_address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('store_address') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap toko...">{{ old('store_address', $settings->store_address) }}</textarea>
                            @error('store_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Store Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="store_latitude" class="block text-sm font-medium text-gray-700">
                                    Latitude Toko
                                </label>
                                <input type="number" step="0.00000001" name="store_latitude" id="store_latitude"
                                    value="{{ old('store_latitude', $settings->store_latitude) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('store_latitude') border-red-500 @enderror"
                                    required>
                                @error('store_latitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Contoh: -6.200000</p>
                            </div>

                            <div>
                                <label for="store_longitude" class="block text-sm font-medium text-gray-700">
                                    Longitude Toko
                                </label>
                                <input type="number" step="0.00000001" name="store_longitude" id="store_longitude"
                                    value="{{ old('store_longitude', $settings->store_longitude) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('store_longitude') border-red-500 @enderror"
                                    required>
                                @error('store_longitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Contoh: 106.816666</p>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Pilih Lokasi Toko di Peta
                                </label>
                                <x-secondary-button type="button" id="use-my-location-btn" class="px-3 py-1.5 text-xs">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gunakan Lokasi Saya
                                </x-secondary-button>
                            </div>
                            <div id="store-map" class="border border-gray-300"></div>
                            <p class="mt-2 text-xs text-gray-500">
                                <strong>Cara pakai:</strong> Seret (drag) marker merah ke lokasi toko Anda, atau klik
                                tombol "Gunakan Lokasi Saya" untuk menggunakan lokasi saat ini.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="free_shipping_radius" class="block text-sm font-medium text-gray-700">
                                    Radius Gratis Ongkir (meter)
                                </label>
                                <input type="number" name="free_shipping_radius" id="free_shipping_radius"
                                    value="{{ old('free_shipping_radius', $settings->free_shipping_radius) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('free_shipping_radius') border-red-500 @enderror"
                                    required min="0">
                                @error('free_shipping_radius')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ number_format($settings->free_shipping_radius / 1000, 1) }} km
                                </p>
                            </div>

                            <div>
                                <label for="max_delivery_distance" class="block text-sm font-medium text-gray-700">
                                    Jarak Maksimal Pengiriman (meter)
                                </label>
                                <input type="number" name="max_delivery_distance" id="max_delivery_distance"
                                    value="{{ old('max_delivery_distance', $settings->max_delivery_distance) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('max_delivery_distance') border-red-500 @enderror"
                                    required min="0">
                                @error('max_delivery_distance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ number_format($settings->max_delivery_distance / 1000, 1) }} km
                                </p>
                            </div>

                            <div>
                                <label for="shipping_cost" class="block text-sm font-medium text-gray-700">
                                    Biaya Ongkir (Rupiah)
                                </label>
                                <input type="number" name="shipping_cost" id="shipping_cost"
                                    value="{{ old('shipping_cost', $settings->shipping_cost) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('shipping_cost') border-red-500 @enderror"
                                    required min="0">
                                @error('shipping_cost')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Rp {{ number_format($settings->shipping_cost, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-800 mb-2"><i class="fas fa-info-circle"></i>
                                Informasi</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Radius Gratis Ongkir:</strong> Jarak dari toko di mana pelanggan mendapat
                                    gratis ongkir</li>
                                <li>• <strong>Jarak Maksimal:</strong> Jarak terjauh yang bisa dilayani untuk pengiriman
                                </li>
                                <li>• <strong>Biaya Ongkir:</strong> Biaya yang dikenakan jika di luar zona gratis
                                    ongkir</li>
                                <li>• <strong>Koordinat:</strong> Gunakan Google Maps untuk mendapatkan koordinat yang
                                    akurat</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                Simpan Pengaturan
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            // Inisialisasi peta
            var map;
            var storeMarker;

            // Koordinat awal dari database
            var initialLat = {{ old('store_latitude', $settings->store_latitude) }};
            var initialLng = {{ old('store_longitude', $settings->store_longitude) }};

            // Inisialisasi peta dengan koordinat toko saat ini
            map = L.map('store-map').setView([initialLat, initialLng], 15);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Icon untuk marker toko
            var storeIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Tambahkan marker yang bisa di-drag
            storeMarker = L.marker([initialLat, initialLng], {
                icon: storeIcon,
                draggable: true
            }).addTo(map);

            storeMarker.bindPopup(
                    "<b><i class='fas fa-map-marker-alt'></i> Lokasi Toko</b><br>Seret marker untuk mengubah lokasi")
                .openPopup();

            // Fungsi untuk update input koordinat
            function updateCoordinates(lat, lng) {
                document.getElementById('store_latitude').value = lat.toFixed(8);
                document.getElementById('store_longitude').value = lng.toFixed(8);

                // Update popup
                storeMarker.setPopupContent(
                    `<b><i class='fas fa-map-marker-alt'></i> Lokasi Toko</b><br>` +
                    `Lat: ${lat.toFixed(6)}<br>` +
                    `Lng: ${lng.toFixed(6)}`
                );
                storeMarker.openPopup();
            }

            // Event saat marker selesai di-drag
            storeMarker.on('dragend', function(e) {
                var newLat = e.target.getLatLng().lat;
                var newLng = e.target.getLatLng().lng;
                updateCoordinates(newLat, newLng);
            });

            // Event saat input koordinat diubah manual
            document.getElementById('store_latitude').addEventListener('change', function() {
                var lat = parseFloat(this.value);
                var lng = parseFloat(document.getElementById('store_longitude').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    storeMarker.setLatLng([lat, lng]);
                    map.setView([lat, lng], map.getZoom());
                }
            });

            document.getElementById('store_longitude').addEventListener('change', function() {
                var lat = parseFloat(document.getElementById('store_latitude').value);
                var lng = parseFloat(this.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    storeMarker.setLatLng([lat, lng]);
                    map.setView([lat, lng], map.getZoom());
                }
            });

            // Tombol "Gunakan Lokasi Saya"
            document.getElementById('use-my-location-btn').addEventListener('click', function() {
                const btn = this;
                const originalText = btn.innerHTML;

                // Tampilkan loading
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mendapatkan lokasi...
                `;

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            var userLat = position.coords.latitude;
                            var userLng = position.coords.longitude;

                            // Update marker dan peta
                            storeMarker.setLatLng([userLat, userLng]);
                            map.setView([userLat, userLng], 15);
                            updateCoordinates(userLat, userLng);

                            // Kembalikan tombol
                            btn.disabled = false;
                            btn.innerHTML = originalText;

                            // Tampilkan notifikasi sukses
                            showNotification('Lokasi berhasil digunakan!', 'success');
                        },
                        function(error) {
                            // Kembalikan tombol
                            btn.disabled = false;
                            btn.innerHTML = originalText;

                            // Tampilkan error
                            let errorMsg = 'Gagal mendapatkan lokasi. ';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg += 'Izin lokasi ditolak.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg += 'Informasi lokasi tidak tersedia.';
                                    break;
                                case error.TIMEOUT:
                                    errorMsg += 'Waktu permintaan habis.';
                                    break;
                                default:
                                    errorMsg += 'Terjadi kesalahan.';
                            }
                            showNotification(errorMsg, 'error');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showNotification('Browser Anda tidak mendukung geolocation.', 'error');
                }
            });

            // Pastikan peta di-render dengan benar
            setTimeout(() => {
                map.invalidateSize();
            }, 100);

            // Handle form submission dengan loading dan notification
            document.getElementById('store-settings-form').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Tampilkan loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
            });
        </script>
    @endpush
</x-app-layout>
