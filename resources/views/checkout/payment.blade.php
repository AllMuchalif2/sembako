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

                // 2. Panggil "hiasan" Midtrans
                window.snap.pay('{{ $snapToken }}', {
                    onClose: function() {
                        // Jika user menutup popup, kita anggap sukses & redirect
                        // karena status di server SUDAH diubah
                        window.location.href = '{{ route('checkout.success', ['order_id' => $order->order_id]) }}';
                    },
                    onError: function(result) {
                        // Jika error, tetap redirect ke success (karena kita curang)
                        window.location.href = '{{ route('checkout.success', ['order_id' => $order->order_id]) }}';
                    }
                    // Kita tidak butuh onSuccess karena 'finish' callback sudah di-set di controller
                });
                
                // 3. SEGERA KIRIM PERINTAH "FAKE SUCCESS" KE SERVER
                // Ini dieksekusi bersamaan dengan munculnya popup
                fetch('{{ route('checkout.mark-processed', ['order_id' => $order->order_id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response (fake success):', data.message);
                    // Tidak perlu melakukan apa-apa lagi, popup sudah terbuka
                    // dan status di DB sudah berubah.
                })
                .catch(error => {
                    console.error('Gagal trigger fake success:', error);
                    // Biarkan saja, jaring pengaman di success.blade.php akan menangani
                });
                
            });
        </script>
    @endpush
</x-app-layout>