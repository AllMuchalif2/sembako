<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>
    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <!-- Breadcrumb -->
                <nav class="flex " aria-label="Breadcrumb">
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Kategori</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 md:me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden md:inline">Tambah Kategori</span>
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Kontainer Tabel -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="categoriesTb" class="min-w-full w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left w-1">
                                        No.</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left w-auto">
                                        Nama</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left w-2">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($categories as $category)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-4 text-center">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $category->name }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <button type="button" title="Lihat Kategori"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 show-category-button"
                                                    data-id="{{ $category->id }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    title="Edit Kategori"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

<form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus Kategori"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Category Modal -->
    <x-modal name="show-category-modal" :show="false" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900" x-text="`Detail Kategori: ${category.name}`">
            </h2>

            <div class="mt-6 space-y-4">
                <div>
                    <x-input-label for="name" value="Nama" />
                    <p class="mt-1 text-sm text-gray-700" x-text="category.name"></p>
                </div>
                <div>
                    <x-input-label for="description" value="Deskripsi" />
                    <p class="mt-1 text-sm text-gray-700" x-text="category.description || '-'"></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Tutup') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
