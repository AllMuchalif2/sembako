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
                                placeholder="Contoh: Jl. Pahlawan No. 123, Kel. Suka Maju, Kec. Damai" required>{{ old('shipping_address',$user->address ?? "") }}</textarea>
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

                            <!-- Form Promo Code -->
                            <div class="mt-6">
                                <div id="promo-form-container" class="{{ Session::has('promo') ? 'hidden' : '' }}">
                                    <label for="promo_code" class="block text-sm font-medium text-gray-700">Punya kode promo?</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input type="text" name="promo_code" id="promo_code" class="block w-full flex-1 rounded-none rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm uppercase">
                                        <button type="button" id="apply-promo-btn" class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            Terapkan
                                        </button>
                                    </div>
                                </div>
                                <!-- Applied Promo Display -->
                                <div id="applied-promo-container" class="{{ Session::has('promo') ? '' : 'hidden' }}">
                                    <p class="text-sm font-medium text-gray-700">Promo diterapkan:</p>
                                    <div class="mt-1 flex items-center justify-between rounded-md bg-green-50 border border-green-200 px-3 py-2">
                                        <span id="promo-code-display" class="text-sm font-bold text-green-800 uppercase">{{ Session::get('promo.code') }}</span>
                                        <button type="button" id="remove-promo-btn" class="text-gray-400 hover:text-red-600" title="Hapus Promo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div id="promo-feedback" class="text-xs mt-2"></div>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <dl class="space-y-2 text-sm text-gray-700">
                                    <div class="flex justify-between">
                                        <dt>Subtotal</dt>
                                        <dd class="font-medium" id="subtotal-amount">Rp{{ number_format($subtotal, 0, ',', '.') }}</dd>
                                    </div>
                                    <div id="discount-row" class="flex justify-between {{ Session::has('promo') ? '' : 'hidden' }}">
                                        <dt class="flex items-center">
                                            <span>Diskon</span>
                                        </dt>
                                        <dd id="discount-amount" class="font-medium text-green-600">-Rp{{ number_format(Session::get('promo.discount_amount', 0), 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900">
                                        <dt>Total</dt>
                                        <dd id="final-total-amount">Rp{{ number_format($finalTotal, 0, ',', '.') }}</dd>
                                    </div>
                                </dl>

                                <p class="mt-2 text-xs text-gray-500">Pajak dan ongkir akan dihitung nanti.</p>

                                <div class="mt-6">
                                    <button type="submit" id="checkout-button"
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
            // --- LOGIKA PETA LEAFLET ---
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

            // --- LOGIKA PROMO CODE ---
            document.addEventListener('DOMContentLoaded', function() {
                const applyBtn = document.getElementById('apply-promo-btn');
                const removeBtn = document.getElementById('remove-promo-btn');
                const promoInput = document.getElementById('promo_code');
                const feedbackDiv = document.getElementById('promo-feedback');
                const discountRow = document.getElementById('discount-row');
                const discountAmountEl = document.getElementById('discount-amount');
                const finalTotalAmountEl = document.getElementById('final-total-amount');
                const promoCodeDisplay = document.getElementById('promo-code-display');
                const promoFormContainer = document.getElementById('promo-form-container');
                const appliedPromoContainer = document.getElementById('applied-promo-container');

                applyBtn.addEventListener('click', function() {
                    const promoCode = promoInput.value.trim();
                    if (!promoCode) {
                        showFeedback('Silakan masukkan kode promo.', 'red');
                        return;
                    }

                    setLoading(applyBtn, true);

                    fetch('{{ route('promo.apply') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ promo_code: promoCode })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showFeedback(data.message, 'green');
                            updateSummary(data.discount_formatted, data.new_total_formatted, promoCode);
                        } else {
                            showFeedback(data.message, 'red');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFeedback('Terjadi kesalahan. Silakan coba lagi.', 'red');
                    })
                    .finally(() => {
                        setLoading(applyBtn, false);
                    });
                });

                removeBtn.addEventListener('click', function() {
                    setLoading(removeBtn, true);
                    showFeedback('', 'green'); // Clear feedback

                    fetch('{{ route('promo.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showFeedback(data.message, 'green');
                            resetSummary(data.new_total_formatted);
                        } else {
                            showFeedback(data.message || 'Gagal menghapus promo.', 'red');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFeedback('Terjadi kesalahan. Silakan coba lagi.', 'red');
                    })
                    .finally(() => {
                        setLoading(removeBtn, false);
                    });
                });

                function showFeedback(message, color) {
                    feedbackDiv.textContent = message;
                    feedbackDiv.className = `text-xs mt-2 text-${color}-600`;
                }

                function updateSummary(discount, total, code) {
                    discountRow.classList.remove('hidden');
                    discountAmountEl.textContent = discount;
                    finalTotalAmountEl.textContent = total; 
                    promoCodeDisplay.textContent = code.toUpperCase();
                    promoFormContainer.classList.add('hidden');
                    appliedPromoContainer.classList.remove('hidden');
                }

                function resetSummary(newTotal) {
                    discountRow.classList.add('hidden');
                    finalTotalAmountEl.textContent = newTotal;
                    promoInput.value = ''; // Clear input field
                    promoFormContainer.classList.remove('hidden');
                    appliedPromoContainer.classList.add('hidden');
                }

                function setLoading(button, isLoading) {
                    button.disabled = isLoading;

                    if (isLoading) {
                        if (button.id === 'apply-promo-btn') {
                            // Simpan konten asli sebelum mengubahnya
                            button.dataset.originalContent = button.innerHTML;
                            button.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                        } else {
                            // Spinner for remove button
                            button.classList.add('animate-spin');
                        }
                    } else {
                        if (button.id === 'apply-promo-btn' && button.dataset.originalContent) {
                            // Kembalikan konten asli
                            button.innerHTML = button.dataset.originalContent;
                        }
                        button.classList.remove('animate-spin'); // Ini akan bekerja untuk tombol hapus
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
