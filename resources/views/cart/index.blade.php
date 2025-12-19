<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Anda') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    @if (count($cartItems) > 0)
                        <div class="overflow-x-auto">

                            <!-- Desktop View: Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Harga
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kuantitas
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Hapus</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($cartItems as $id => $item)
                                            <tr data-product-id="{{ $id }}"
                                                data-update-url="{{ route('cart.update', $id) }}"
                                                data-remove-url="{{ route('cart.remove', $id) }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-16 w-16">
                                                            <img class="h-16 w-16 rounded-md object-cover"
                                                                src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/150' }}"
                                                                alt="{{ $item['name'] }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <a href="#"
                                                                    class="hover:text-blue-600 show-product-modal-button"
                                                                    data-slug="{{ $item['slug'] }}">{{ $item['name'] }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Rp{{ number_format($item['price'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="flex items-center space-x-2">
                                                            @if ($item['quantity'] > 1)
                                                                <form action="{{ route('cart.update', $id) }}"
                                                                    method="POST"
                                                                    class="inline update-cart-form btn-minus-form">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="quantity"
                                                                        value="{{ $item['quantity'] - 1 }}">
                                                                    <button type="submit"
                                                                        class="w-8 h-8 rounded-md bg-gray-200 hover:bg-gray-300 flex items-center justify-center btn-minus">
                                                                        <i class="fas fa-minus text-xs"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('cart.remove', $id) }}"
                                                                    method="POST"
                                                                    class="inline remove-from-cart-form btn-minus-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <!-- Hidden quantity input for structure consistency (ignored by delete) -->
                                                                    <input type="hidden" name="quantity"
                                                                        value="0">
                                                                    <button type="submit"
                                                                        class="w-8 h-8 rounded-md bg-gray-200 hover:bg-gray-300 flex items-center justify-center btn-minus">
                                                                        <i class="fas fa-minus text-xs"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <form action="{{ route('cart.update', $id) }}"
                                                                method="POST" class="inline update-cart-form">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="number" name="quantity"
                                                                    value="{{ $item['quantity'] }}" min="1"
                                                                    max="{{ $item['stock'] ?? '' }}"
                                                                    class="w-16 text-center font-medium border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mx-1 p-1 text-sm remove-arrow item-quantity"
                                                                    oninput="if(this.max && parseInt(this.value) > parseInt(this.max)) this.value = this.max;"
                                                                    onchange="this.form.requestSubmit()">
                                                            </form>
                                                            <form action="{{ route('cart.update', $id) }}"
                                                                method="POST" class="inline update-cart-form">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="quantity"
                                                                    value="{{ $item['quantity'] + 1 }}">
                                                                <button type="submit"
                                                                    class="w-8 h-8 rounded-md bg-gray-200 hover:bg-gray-300 flex items-center justify-center btn-plus"
                                                                    {{ $item['quantity'] >= ($item['stock'] ?? 1) ? 'disabled' : '' }}>
                                                                    <i class="fas fa-plus text-xs"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <p class="text-xs text-gray-400 mt-1 item-stock">Stok:
                                                            {{ $item['stock'] ?? 'N/A' }}
                                                        </p>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 item-subtotal">
                                                    Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('cart.remove', $id) }}" method="POST"
                                                        class="remove-from-cart-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile View: Cards -->
                            <div class="md:hidden space-y-4">
                                @foreach ($cartItems as $id => $item)
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                                        data-product-id="{{ $id }}"
                                        data-update-url="{{ route('cart.update', $id) }}"
                                        data-remove-url="{{ route('cart.remove', $id) }}">
                                        <!-- Product Info -->
                                        <div class="flex items-start space-x-4 mb-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-20 w-20 rounded-md object-cover"
                                                    src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/150' }}"
                                                    alt="{{ $item['name'] }}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('product.show', $item['slug']) }}"
                                                    class="text-sm font-medium text-gray-900 hover:text-blue-600 block">
                                                    {{ $item['name'] }}
                                                </a>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Rp{{ number_format($item['price'], 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1 item-stock">Stok tersedia:
                                                    {{ $item['stock'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls and Price -->
                                        <!-- Quantity Controls and Price -->
                                        <div class="flex items-center justify-between mt-4">
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center space-x-3">
                                                @if ($item['quantity'] > 1)
                                                    <form action="{{ route('cart.update', $id) }}" method="POST"
                                                        class="inline update-cart-form btn-minus-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity"
                                                            value="{{ $item['quantity'] - 1 }}">
                                                        <button type="submit"
                                                            class="w-9 h-9 rounded-md bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors btn-minus">
                                                            <i class="fas fa-minus text-sm text-gray-600"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('cart.remove', $id) }}" method="POST"
                                                        class="inline remove-from-cart-form btn-minus-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="quantity" value="0">
                                                        <button type="submit"
                                                            class="w-9 h-9 rounded-md bg-gray-100 hover:bg-red-200 flex items-center justify-center transition-colors btn-minus">
                                                            <i class="fas fa-minus text-sm text-gray-600"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('cart.update', $id) }}" method="POST"
                                                    class="inline update-cart-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity"
                                                        value="{{ $item['quantity'] }}" min="1"
                                                        max="{{ $item['stock'] ?? '' }}"
                                                        class="w-16 text-center font-medium border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-1 text-sm remove-arrow item-quantity"
                                                        oninput="if(this.max && parseInt(this.value) > parseInt(this.max)) this.value = this.max;"
                                                        onchange="this.form.requestSubmit()">
                                                </form>

                                                <form action="{{ route('cart.update', $id) }}" method="POST"
                                                    class="inline update-cart-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="quantity"
                                                        value="{{ $item['quantity'] + 1 }}">
                                                    <button type="submit"
                                                        class="w-9 h-9 rounded-md bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors btn-plus {{ $item['quantity'] >= ($item['stock'] ?? 1) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $item['quantity'] >= ($item['stock'] ?? 1) ? 'disabled' : '' }}>
                                                        <i class="fas fa-plus text-sm text-gray-600"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Subtotal and Delete -->
                                            <div class="flex items-center space-x-3">
                                                <div class="text-right">
                                                    <p class="text-xs text-gray-500">Subtotal</p>
                                                    <p class="text-sm font-bold text-gray-900 item-subtotal">
                                                        Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <form action="{{ route('cart.remove', $id) }}" method="POST"
                                                    class="remove-from-cart-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-9 h-9 rounded-md bg-red-50 hover:bg-red-100 flex items-center justify-center transition-colors">
                                                        <i class="fas fa-trash-alt text-sm text-red-600"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Stock Warning (if quantity near stock limit) -->
                                        @if (isset($item['stock']) && $item['quantity'] >= $item['stock'])
                                            <div class="mt-3 p-2 bg-yellow-50 rounded-md">
                                                <p class="text-xs text-yellow-700 flex items-center">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Stok terbatas
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <!-- Total dan Checkout -->
                        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <p>Subtotal</p>
                                <p class="cart-total">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <p class="mt-0.5 text-sm text-gray-500">Biaya pengiriman akan dihitung saat checkout.</p>
                            <div class="mt-6">
                                <a href="{{ route('checkout.index') }}"
                                    class="flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 {{ count($cartItems) == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ count($cartItems) == 0 ? 'aria-disabled="true"' : '' }}>
                                    Lanjutkan ke Pembayaran
                                </a>
                            </div>
                            <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                <p>
                                    atau
                                    <a href="{{ route('products.index') }}"
                                        class="font-medium text-blue-600 hover:text-blue-500">
                                        Lanjutkan Belanja
                                        <span aria-hidden="true"> &rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Keranjang Kosong -->
                        <div class="text-center py-16 px-4 sm:px-6 lg:px-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Keranjang Anda kosong</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Sepertinya Anda belum menambahkan produk apapun.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('products.index') }}"
                                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                    Mulai Belanja
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
