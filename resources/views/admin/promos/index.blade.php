<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Promo') }}
        </h2>
    </x-slot>

    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Promo</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('admin.promos.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest">
                    <x-primary-button>
                        {{ __('Tambah Promo') }}
                    </x-primary-button>
                </a>
            </div>

            <!-- Kontainer Tabel -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full text-sm" id="promosTb">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Kode</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Tipe</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Nilai</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Periode</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Kuota</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Status</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-gray-600 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($promos as $promo)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <div class="text-sm font-mono font-bold text-gray-900">{{ $promo->code }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                                {{ Str::limit($promo->description, 50) }}</div>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            {{ $promo->type == 'fixed' ? 'Tetap' : 'Persen' }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if ($promo->type == 'fixed')
                                                Rp{{ number_format($promo->value, 0, ',', '.') }}
                                            @else
                                                {{ $promo->value }}%
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            {{ $promo->start_date->format('d M Y') }} -
                                            {{ $promo->end_date->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            {{ $promo->times_used }} / {{ $promo->usage_limit ?? '∞' }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if ($promo->status == 'active' && $promo->end_date->isFuture())
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak
                                                    Aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.promos.edit', $promo) }}" title="Edit Promo"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.promos.destroy', $promo) }}"
                                                    method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus Promo"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                            Belum ada promo yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
