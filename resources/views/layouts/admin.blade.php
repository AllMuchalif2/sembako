<div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300" 
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" 
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden">
</div>

<aside x-data="{ userMenuOpen: false }" 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:static inset-y-0 left-0 z-50 bg-white border-r border-gray-100 min-h-screen w-64 transform transition-transform duration-300 ease-in-out flex flex-col">

    {{-- Logo --}}
    <div class="p-6 border-b border-gray-100">
        <a href="{{ route('admin.dashboard') }}" class="flex justify-center">
            <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
        </a>
    </div>

    {{-- User Dropdown --}}
    <div class="border-b border-gray-100 px-4 py-4">
        <button @click="userMenuOpen = !userMenuOpen"
            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
            <div class="flex items-center">
                <i class="fa-solid fa-user-circle mr-3 text-lg"></i>
                <span>{{ Auth::user()->name }}</span>
            </div>
            <i class="fa-solid fa-chevron-down w-4 h-4 transition-transform duration-200"
                :class="{ 'rotate-180': userMenuOpen }"></i>
        </button>

        <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" class="mt-1 space-y-1">

            <a href="{{ route('admin.profile.edit') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-user-edit mr-3"></i>
                {{ __('Profile') }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors text-left">
                    <i class="fa-solid fa-sign-out-alt mr-3"></i>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 px-4 py-6 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.dashboard') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-home mr-3"></i>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.categories.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-tags mr-3"></i>
            {{ __('Kelola Kategori') }}
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.products.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-box mr-3"></i>
            {{ __('Kelola Produk') }}
        </a>

        <a href="{{ route('admin.promos.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.promos.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-percent mr-3"></i>
            {{ __('Kelola Promo') }}
        </a>

        <a href="{{ route('admin.transactions.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.transactions.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-receipt mr-3"></i>
            {{ __('Riwayat Transaksi') }}
        </a>

        <a href="{{ route('admin.admins.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.admins.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-users-cog mr-3"></i>
            {{ __('Kelola Admin') }}
        </a>

        <a href="{{ route('admin.store-settings.edit') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors 
            {{ request()->routeIs('admin.store-settings.*') 
                ? 'bg-gray-100 text-gray-900' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fa-solid fa-cog mr-3"></i>
            {{ __('Pengaturan Toko') }}
        </a>

        
    </nav>

    {{-- Theme Toggle (Dark/Light) --}}
    {{-- <div class="p-4 border-t border-gray-100 flex justify-center">
        <x-theme-toggle />
    </div> --}}

</aside>