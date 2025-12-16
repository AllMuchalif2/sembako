<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Aktivitas (Audit Log)') }}
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Riwayat
                                    Aktivitas</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                            <div>
                                <label for="causer_id" class="block text-sm font-medium text-gray-700">Oleh
                                    User</label>
                                <select name="causer_id" id="causer_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                                    <option value="">Semua</option>
                                    <option value="null" @selected(request('causer_id') == 'null')>System</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" @selected(request('causer_id') == $admin->id)>
                                            {{ $admin->name }} ({{ $admin->role->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="event" class="block text-sm font-medium text-gray-700">Tipe Event</label>
                                <select name="event" id="event"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Event</option>
                                    <option value="created" @selected(request('event') == 'created')>Created</option>
                                    <option value="updated" @selected(request('event') == 'updated')>Updated</option>
                                    <option value="deleted" @selected(request('event') == 'deleted')>Deleted</option>
                                </select>
                            </div>
                            <div>
                                <label for="subject_type"
                                    class="block text-sm font-medium text-gray-700">Entitas</label>
                                <select name="subject_type" id="subject_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Entitas</option>
                                    <option value="App\Models\User" @selected(request('subject_type') == 'App\Models\User')>User</option>
                                    <option value="App\Models\Product" @selected(request('subject_type') == 'App\Models\Product')>Product</option>
                                    <option value="App\Models\Category" @selected(request('subject_type') == 'App\Models\Category')>Category</option>
                                    <option value="App\Models\Transaction" @selected(request('subject_type') == 'App\Models\Transaction')>Transaction
                                    </option>
                                    <option value="App\Models\Promo" @selected(request('subject_type') == 'App\Models\Promo')>Promo</option>
                                </select>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Akhir</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-1.5">
                            </div>
                            <div
                                class="col-span-full sm:col-span-2 lg:col-span-1 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                <x-primary-button class="justify-center">
                                    Filter
                                </x-primary-button>
                                <x-secondary-button href="{{ route('admin.activity-logs.index') }}"
                                    class="justify-center">
                                    Reset
                                </x-secondary-button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">No
                                    </th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Waktu
                                    </th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Pelaku
                                    </th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Aksi
                                    </th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">
                                        Entitas</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-gray-600 text-left">Detail
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($activities as $activity)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            {{ $activities->firstItem() + $loop->index }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            {{ $activity->created_at->locale('id')->translatedFormat('d M Y H:i:s') }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                            @if ($activity->causer)
                                                <span
                                                    class="text-xs text-gray-500 block">({{ $activity->causer->role->name ?? '-' }})</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm capitalize">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if ($activity->event == 'created') bg-green-100 text-green-800
                                                @elseif($activity->event == 'updated') bg-yellow-100 text-yellow-800
                                                @elseif($activity->event == 'deleted') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $activity->description }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap text-sm">
                                            @php
                                                $subjectName = class_basename($activity->subject_type);
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $subjectName }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-1">ID:
                                                {{ $activity->subject_id }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-sm" x-data="{ open: false }">
                                            @if ($activity->properties && $activity->properties->count() > 0)
                                                <button @click="open = true"
                                                    class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-md transition ease-in-out duration-150">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <!-- Modal -->
                                                <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto"
                                                    style="display: none;" x-transition>
                                                    <div
                                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div class="fixed inset-0 transition-opacity"
                                                            aria-hidden="true" @click="open = false">
                                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                        </div>
                                                        <span
                                                            class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                            aria-hidden="true">&#8203;</span>
                                                        <div
                                                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <h3
                                                                    class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                                                    Detail Perubahan</h3>
                                                                <div class="mt-2 text-sm text-gray-600">
                                                                    @if (isset($activity->properties['attributes']))
                                                                        <div class="space-y-2">
                                                                            @foreach ($activity->properties['attributes'] as $key => $newValue)
                                                                                @php
                                                                                    // Skip internal fields
                                                                                    if (
                                                                                        in_array($key, [
                                                                                            'created_at',
                                                                                            'updated_at',
                                                                                            'deleted_at',
                                                                                        ])
                                                                                    ) {
                                                                                        continue;
                                                                                    }

                                                                                    $oldValue =
                                                                                        $activity->properties['old'][
                                                                                            $key
                                                                                        ] ?? null;
                                                                                @endphp
                                                                                <div class="bg-gray-50 p-2 rounded">
                                                                                    <span
                                                                                        class="font-bold block text-xs uppercase text-gray-500">{{ $key }}</span>
                                                                                    @if ($activity->event == 'updated' && isset($activity->properties['old']))
                                                                                        <div
                                                                                            class="grid grid-cols-2 gap-2 mt-1">
                                                                                            <div
                                                                                                class="text-red-600 line-through text-xs">
                                                                                                {{ $oldValue ?? '(kosong)' }}
                                                                                            </div>
                                                                                            <div
                                                                                                class="text-green-600 font-semibold text-xs">
                                                                                                {{ $newValue }}
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        <div
                                                                                            class="text-gray-800 text-sm mt-1">
                                                                                            {{ $newValue }}</div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <!-- Fallback for simple logs or other formats -->
                                                                        <pre class="bg-gray-100 p-2 rounded text-xs">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <x-secondary-button @click="open = false"
                                                                    class="w-full sm:w-auto justify-center sm:ml-3">
                                                                    Tutup
                                                                </x-secondary-button>
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
