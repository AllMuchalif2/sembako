<x-app-layout>
    {{-- Hero Section --}}
    <div class="bg-white">
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Sembako Online Terlengkap</h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Belanja kebutuhan pokok harian Anda dengan mudah, cepat, dan aman. Kualitas terjamin, harga bersahabat.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6" x-data>
                        <a href="#produk" x-on:click.prevent="document.getElementById('produk').scrollIntoView({ behavior: 'smooth' })" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Mulai Belanja</a>
                        <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Pelajari Lebih Lanjut <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Products Section --}}
    <div id="produk" class="bg-gray-100 scroll-mt-16">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl text-center">Produk Terbaru Kami</h2>

            @if($products->isEmpty())
                <div class="mt-6 text-center text-gray-500">
                    <p>Saat ini belum ada produk yang tersedia.</p>
                </div>
            @else
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach ($products as $product) 
                        <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-lg">
                            <div class="aspect-h-1 aspect-w-1 bg-gray-200 sm:h-auto">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:opacity-75">
                            </div>
                            <div class="flex flex-1 flex-col space-y-2 p-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('product.show', $product) }}" class="show-product-modal-button" data-slug="{{ $product->slug }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            </div> 
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Lihat Semua --}}
                <div class="mt-12 text-center">
                    <a href="/products" class="rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center py-8">
                <x-application-logo class="block h-10 w-auto fill-current text-gray-800" />
                <p class="mt-4 text-center text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'Sembako') }}. All rights reserved.</p>
                <div class="mt-4 flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Facebook</span>
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Instagram</span>
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Twitter</span>
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</x-app-layout>