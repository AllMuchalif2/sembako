<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('landing')" :active="request()->routeIs('landing')">
                        {{ __('Beranda') }}
                    </x-nav-link>

                    @php
                        $isProductsActive = request()->routeIs('products.index');
                    @endphp
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                        class="relative inline-flex items-center px-1 pt-1 border-b-2 {{ $isProductsActive ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out cursor-pointer text-sm font-semibold leading-5">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center">
                            {{ __('Produk') }}
                            <div class="ml-1">
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                        </a>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 top-full mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('products.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !request()->has('category') && $isProductsActive ? 'bg-gray-50 font-semibold' : '' }}">
                                    {{ __('Semua Produk') }}
                                </a>
                                <div class="border-t border-gray-100"></div>
                                @forelse($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->input('category') == $category->slug ? 'bg-gray-50 font-semibold' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @empty
                                    <span class="block px-4 py-2 text-sm text-gray-500">Tidak ada kategori</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" class="relative inline-flex items-center gap-1">
                        {{ __('Keranjang') }}
                        <span
                            class="cart-count inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-blue-600 rounded-full @if (!session('cart') || count(session('cart')) == 0) hidden @endif">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </x-nav-link>



                    {{-- Tambahkan link lain di sini, misal: Produk, Tentang Kami --}}
                </div>
            </div>

            <!-- Settings Dropdown / Login & Register -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <i class="fa-solid fa-angle-down"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if (Auth::user()->role_id == '1' || Auth::user()->role_id == '2')
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Dashboard admin') }}
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('customer.dashboard')">
                                    {{ __('Akun Saya') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('transactions.index')">
                                    {{ __('Riwayat Transaksi') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>

                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Log
                        in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ms-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Register</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (akan muncul di mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        {{-- Tambahkan menu responsive di sini jika diperlukan --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('landing')" :active="request()->routeIs('landing')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                {{ __('Produk') }}
            </x-responsive-nav-link>

            {{-- Categories in mobile menu --}}
            <div x-data="{ categoriesOpen: false }" class="border-t border-gray-200 pt-2 mt-2">
                <button @click="categoriesOpen = !categoriesOpen"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-50 transition">
                    <span>{{ __('Kategori') }}</span>
                    <i class="fa-solid fa-angle-down h-4 w-4 transition-transform duration-200"
                        :class="{ 'rotate-180': categoriesOpen }"></i>
                </button>

                <div x-show="categoriesOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 -translate-y-2"
                    x-transition:enter-end="transform opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 translate-y-0"
                    x-transition:leave-end="transform opacity-0 -translate-y-2" style="display: none;">
                    @forelse($categories as $category)
                        <x-responsive-nav-link :href="route('products.index', ['category' => $category->slug])" :active="request()->input('category') == $category->slug">
                            {{ $category->name }}
                        </x-responsive-nav-link>
                    @empty
                        <div class="px-4 py-2 text-sm text-gray-500">
                            Tidak ada kategori
                        </div>
                    @endforelse
                </div>
            </div>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                {{ __('Keranjang') }}
                @if (session('cart') && count(session('cart')) > 0)
                    <span
                        class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-blue-600 rounded-full cart-count">
                        {{ count(session('cart')) }}
                    </span>
                @else
                    <span
                        class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-blue-600 rounded-full cart-count hidden">
                        0
                    </span>
                @endif
            </x-responsive-nav-link>


        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if (Auth::user()->role_id == '0')
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            {{ __('Dashboard Admin') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('customer.dashboard')">
                            {{ __('Akun Saya') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('transactions.index')">
                            {{ __('Riwayat Transaksi') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>

    </div>
</nav>
