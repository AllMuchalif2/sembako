<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">
    
    {{-- Logika utama: Cek apakah ini halaman admin. --}}
    @if (request()->routeIs('admin.*'))
        {{-- Layout Admin dengan Sidebar Kustom --}}
        <div x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            init() {
                this.$watch('sidebarOpen', value => {
                    if (value && window.innerWidth < 1024) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            }
        }"
            @resize.window="if (window.innerWidth >= 1024) { sidebarOpen = true; document.body.style.overflow = ''; }"
            class="flex min-h-screen">

            @include('layouts.admin')

            <div class="flex-1 flex flex-col">

                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center gap-4">
                            <button @click="sidebarOpen = !sidebarOpen" type="button"
                                class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors lg:hidden">
                                <i class="fa-solid fa-bars text-xl"></i>
                            </button>

                            <div class="flex-1 text-gray-800">
                                {{ $header }}
                            </div>
                        </div>
                    </header>
                @endif

                <main class="flex-1 p-6 lg:p-8 bg-gray-100">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        {{-- Layout Standar untuk Customer --}}
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
    @endif



    @stack('scripts')
</body>

</html>
