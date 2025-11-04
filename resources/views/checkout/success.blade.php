<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="mt-4 text-2xl font-bold text-gray-800">Pembayaran Diterima!</h2>
                    <p class="mt-2 text-gray-600">Terima kasih telah berbelanja. Pesanan Anda dengan nomor <span
                            class="font-semibold text-gray-900">{{ $order->order_id }}</span> sedang kami proses.</p>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Total Pembayaran: <span class="font-medium text-gray-700">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                        {{-- <p>Status Pembayaran: <span class="font-medium text-green-600 capitalize">{{ $order->payment_status }}</span></p> --}}
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
