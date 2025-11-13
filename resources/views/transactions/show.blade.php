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

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Riwayat
                </a>
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
                                    <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/150' }}"
                                            alt="{{ $item->product_name }}"
                                            class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item->product_name }}</h3>
                                                <p class="ml-4">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
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
                                    <dd class="font-medium text-gray-900">Rp{{ number_format($transaction->total_amount + $transaction->discount_amount, 0, ',', '.') }}</dd>
                                </div>
                                @if($transaction->promo_code)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 flex items-center">
                                        <span>Diskon</span>
                                        <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                            {{ $transaction->promo_code }}
                                        </span>
                                    </dt>
                                    <dd class="font-medium text-green-600">-Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900">
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">Status saat ini:
                                <span class="font-bold text-base
                                    @if ($transaction->status == 'diproses') text-blue-600
                                    @elseif($transaction->status == 'dikirim') text-purple-600
                                    @elseif($transaction->status == 'selesai') text-green-600
                                    @elseif($transaction->status == 'dibatalkan' || $transaction->status == 'failed') text-red-600
                                    @else text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </p>

                            @if ($transaction->status == 'dikirim')
                                <form action="{{ route('transactions.complete', $transaction) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Pesanan Sudah Diterima
                                    </button>
                                </form>
                            @elseif ($transaction->status == 'diproses')
                                <form action="{{ route('transactions.cancel', $transaction) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            var lat = {{ $transaction->latitude ?? 0 }};
            var lng = {{ $transaction->longitude ?? 0 }};
            var map = L.map('map', { dragging: false, zoomControl: false, scrollWheelZoom: false }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
            var marker = L.marker([lat, lng]).addTo(map).bindPopup("<b>Lokasi Pengiriman</b>").openPopup();
        </script>
    @endpush
</x-app-layout>

