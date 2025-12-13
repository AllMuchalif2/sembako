<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Aktivitas (Audit Log)') }}
        </h2>
    </x-slot>

    <div class="p-6 lg:p-8 bg-gray-100 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Waktu</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Pelaku (Admin/User)</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Aksi</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Entitas</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($activities as $activity)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            {{ $activity->created_at->format('d M Y H:i:s') }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                            @if($activity->causer)
                                                <span class="text-xs text-gray-500">({{ $activity->causer->role->name ?? '-' }})</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm capitalize">
                                            {{ $activity->description }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            @php
                                                $subjectName = class_basename($activity->subject_type);
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $subjectName }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-1">ID: {{ $activity->subject_id }}</span>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm" x-data="{ open: false }">
                                            @if($activity->properties && $activity->properties->count() > 0)
                                                <button @click="open = true" class="text-blue-600 hover:text-blue-900 underline text-sm">
                                                    Lihat Perubahan
                                                </button>

                                                <!-- Inline Modal (Simplified) -->
                                                <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
                                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                        </div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detail Perubahan</h3>
                                                                <div class="mt-2 h-64 overflow-y-auto bg-gray-50 p-4 rounded text-xs font-mono">
                                                                    <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                                                                    Tutup
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-6 text-center text-gray-500">
                                            Belum ada aktivitas yang terekam.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
