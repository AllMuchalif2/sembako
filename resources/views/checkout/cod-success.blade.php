<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesanan COD Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="rounded-full bg-green-100 p-6">
                            <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h3>
                    <p class="text-gray-600 mb-6">Pesanan Anda dengan metode pembayaran <span class="font-semibold text-green-600">COD (Bayar di Tempat)</span> telah berhasil dibuat</p>

                    <!-- Order ID Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg mb-8">
                        <i class="fas fa-receipt text-blue-600 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">No. Pesanan:</span>
                        <span class="ml-2 text-sm font-bold text-blue-600">{{ $order->order_id }}</span>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                        <h4 class="font-semibold text-gray-900 mb-4 text-center">Ringkasan Pesanan</h4>
                        
                        <!-- Items -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Produk yang Dibeli:</h5>
                            <ul class="space-y-2">
                                @foreach ($order->items as $item)
                                    <li class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $item->product_name }} (x{{ $item->quantity }})</span>
                                        <span class="font-medium text-gray-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Pricing Details -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">Rp{{ number_format($order->total_amount - $order->shipping_cost + $order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->discount_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Diskon</span>
                                    <span class="text-green-600">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="text-gray-900">{{ $order->shipping_cost > 0 ? 'Rp' . number_format($order->shipping_cost, 0, ',', '.') : 'Gratis' }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-300 pt-2 font-bold text-base">
                                <span class="text-gray-900">Total</span>
                                <span class="text-blue-600">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h5 class="text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman:</h5>
                            <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <!-- Important Info Alert -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-left">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-blue-800 mb-2">Informasi Penting</h4>
                                <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                                    <li>Pesanan Anda sedang menunggu <strong>konfirmasi admin</strong></li>
                                    <li>Siapkan uang tunai sejumlah <strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong> saat barang tiba</li>
                                    <li>Pastikan nomor telepon Anda aktif untuk koordinasi pengiriman</li>
                                    <li>Anda dapat melacak status pesanan di halaman "Transaksi Saya"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('transactions.show', $order) }}" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail Pesanan
                        </a>
                        <a href="{{ route('products.index') }}" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Belanja Lagi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Help Section -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                <h4 class="font-semibold text-gray-900 mb-3 text-center">Butuh Bantuan?</h4>
                <p class="text-sm text-gray-600 text-center">
                    Jika Anda memiliki pertanyaan tentang pesanan ini, silakan hubungi kami atau cek halaman transaksi Anda untuk update status pesanan.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
