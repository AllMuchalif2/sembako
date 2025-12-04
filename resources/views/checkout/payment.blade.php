<x-app-layout>
    @push('head')
        <script type="text/javascript" src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Proses Pembayaran</h2>
                    <p class="text-gray-600 mb-6">Selesaikan pembayaran Anda untuk pesanan <span class="font-semibold">
                            {{ $order->order_id }}</span>. Klik tombol di bawah untuk membayar.</p>

                    <button id="pay-button"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            var payButton = document.getElementById('pay-button');
            
            payButton.addEventListener('click', function() {
                // 1. Nonaktifkan tombol
                payButton.disabled = true;
                payButton.innerText = 'Memproses...';

                // 2. Panggil Midtrans Snap
                window.snap.pay('{{ $snapToken }}', {
                    onClose: function() {
                        // User closed popup without completing - just re-enable the button
                        console.log('Payment popup closed');
                        payButton.disabled = false;
                        payButton.innerText = 'Bayar Sekarang';
                    },
                    onError: function(result) {
                        console.log('Payment error!', result);
                        payButton.disabled = false;
                        payButton.innerText = 'Bayar Sekarang';
                        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                    }
                    // Midtrans will auto-redirect via 'finish' callback after successful payment
                });
                
            });
        </script>
    @endpush
</x-app-layout>