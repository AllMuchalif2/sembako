<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Admin
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="{{ route('admin.admins.index') }}"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">Manajemen
                                Admin</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit:
                                {{ $admin->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Status Indicator & Actions -->
                        <div
                            class="mt-6 p-4 rounded-lg {{ $admin->status ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i
                                        class="fa-solid {{ $admin->status ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600' }} text-xl mr-3"></i>
                                    <div>
                                        <p
                                            class="font-semibold {{ $admin->status ? 'text-green-800' : 'text-red-800' }}">
                                            Status: {{ $admin->status ? 'Aktif' : 'Nonaktif' }}
                                        </p>
                                        <p class="text-sm {{ $admin->status ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $admin->status ? 'Admin dapat login' : 'Admin tidak dapat login' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Reset Password Button -->
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
                                        x-on:click.prevent="$dispatch('open-modal', 'reset-password-modal'); $dispatch('set-reset-action', { url: '{{ route('admin.admins.resetPassword', $admin) }}', name: '{{ $admin->name }}' })">
                                        <i class="fa-solid fa-key mr-2"></i>
                                        Reset Password
                                    </button>

                                    <!-- Toggle Status Button -->
                                    @if ($admin->id !== auth()->id())
                                        <form action="{{ route('admin.admins.toggleStatus', $admin) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 {{ $admin->status ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition">
                                                <i
                                                    class="fa-solid {{ $admin->status ? 'fa-power-off' : 'fa-check' }} mr-2"></i>
                                                {{ $admin->status ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $admin->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $admin->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('No. HP')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                :value="old('phone', $admin->phone)" autocomplete="tel" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div>
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                rows="3">{{ old('address', $admin->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.admins.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <x-modal name="reset-password-modal" :show="false" focusable>
        <div class="p-6" x-data="{ actionUrl: '', adminName: '', showPassword: false }"
            @set-reset-action.window="actionUrl = $event.detail.url; adminName = $event.detail.name">
            <h2 class="text-lg font-medium text-gray-900">
                Reset Password: <span x-text="adminName"></span>
            </h2>

            <form :action="actionUrl" method="POST" class="mt-6">
                @csrf

                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-triangle-exclamation text-yellow-600 mt-0.5 mr-2"></i>
                        <p class="text-sm text-yellow-800">
                            Password admin akan direset. Pastikan memberitahu admin yang bersangkutan.
                        </p>
                    </div>
                </div>

                <div>
                    <x-input-label for="password" value="Password Baru" />
                    <div class="relative">
                        <x-text-input id="password" name="password" ::type="showPassword ? 'text' : 'password'"
                            class="mt-1 block w-full pr-10" required autofocus />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <div class="relative">
                        <x-text-input id="password_confirmation" name="password_confirmation" ::type="showPassword ? 'text' : 'password'"
                            class="mt-1 block w-full pr-10" required />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <x-primary-button
                        class="ml-3 bg-yellow-600 hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:ring-yellow-500">
                        <i class="fa-solid fa-key mr-2"></i>
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
