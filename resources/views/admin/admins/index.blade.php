<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Admin') }}
        </h2>
    </x-slot>

    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Manajemen Admin</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('admin.admins.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest ">
                    <x-primary-button>
                        {{ __('Tambah Admin') }}
                    </x-primary-button>
                </a>
            </div>



            <!-- Kontainer Tabel -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full text-sm" id="adminsTb">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left w-1">No.</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Nama</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Email</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">No. HP</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Status</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left w-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($admins as $admin)
                                    <tr
                                        class="border-b border-gray-200 hover:bg-gray-50 {{ !$admin->status ? 'opacity-60' : '' }}">
                                        <td class="py-3 px-4 text-center">
                                            {{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $admin->name }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $admin->email }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $admin->phone ?? '-' }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if ($admin->status)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fa-solid fa-circle-check mr-1"></i>
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fa-solid fa-circle-xmark mr-1"></i>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                @if ($admin->id !== auth()->id())
                                                    <form action="{{ route('admin.admins.toggleStatus', $admin) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            title="{{ $admin->status ? 'Nonaktifkan Admin' : 'Aktifkan Admin' }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 {{ $admin->status ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                                            <i
                                                                class="fa-solid {{ $admin->status ? 'fa-power-off' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('admin.admins.edit', $admin) }}" title="Edit Admin"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

                                                @if ($admin->id !== auth()->id())
                                                    <form action="{{ route('admin.admins.destroy', $admin) }}"
                                                        method="POST" class="inline delete-form"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Hapus Admin"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $admins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
