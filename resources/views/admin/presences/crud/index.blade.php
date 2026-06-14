<x-admin-layout>
    <div class="space-y-6">

        {{-- ================================================================ --}}
        {{-- HEADER --}}
        {{-- ================================================================ --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kelola Data Absensi</h2>
                <p class="mt-1 text-sm text-gray-600">
                    CRUD data kehadiran guru: Hadir, Terlambat, Sakit, Izin, Alpa
                </p>
            </div>
            <a href="{{ route('admin.presences.crud.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Absensi
            </a>
        </div>

        {{-- ================================================================ --}}
        {{-- FLASH MESSAGES --}}
        {{-- ================================================================ --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ================================================================ --}}
        {{-- STAT CARDS --}}
        {{-- ================================================================ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $statConfig = [
                    'total'       => ['label' => 'Total', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon_color' => 'text-gray-500'],
                    'hadir'       => ['label' => 'Hadir', 'bg' => 'bg-green-50', 'text' => 'text-green-800', 'icon_color' => 'text-green-500'],
                    'terlambat'   => ['label' => 'Terlambat', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-800', 'icon_color' => 'text-yellow-500'],
                    'sakit'       => ['label' => 'Sakit', 'bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'icon_color' => 'text-blue-500'],
                    'izin'        => ['label' => 'Izin', 'bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'icon_color' => 'text-purple-500'],
                    'tidak_hadir' => ['label' => 'Alpa', 'bg' => 'bg-red-50', 'text' => 'text-red-800', 'icon_color' => 'text-red-500'],
                ];
            @endphp
            @foreach($statConfig as $key => $cfg)
                <div class="rounded-xl p-4 shadow-sm border border-gray-200 {{ $cfg['bg'] }}">
                    <p class="text-xs font-semibold uppercase tracking-wider {{ $cfg['icon_color'] }}">{{ $cfg['label'] }}</p>
                    <p class="text-2xl font-bold mt-1 {{ $cfg['text'] }}">{{ $stats[$key] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        {{-- ================================================================ --}}
        {{-- FILTER --}}
        {{-- ================================================================ --}}
        <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-5">
                <form method="GET" action="{{ route('admin.presences.crud.index') }}"
                      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- Guru --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Guru</label>
                        <select name="user_id" id="filter_user"
                                class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-800 px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ ($filters['user_id'] ?? '') == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                        <select name="status" id="filter_status"
                                class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-800 px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $val => $label)
                                <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bulan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Bulan</label>
                        <select name="month" id="filter_month"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ ($filters['month'] ?? '') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Tahun</label>
                        <select name="year" id="filter_year"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Tahun</option>
                            @for($y = 2024; $y <= 2028; $y++)
                                <option value="{{ $y }}" {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.presences.crud.index') }}"
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- TABLE --}}
        {{-- ================================================================ --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guru</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terlambat</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($presences as $i => $p)
                            @php
                                $statusColors = [
                                    'hadir'       => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'terlambat'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'sakit'       => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'izin'        => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                    'tidak_hadir' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                                $statusLabels = [
                                    'hadir'       => 'Hadir',
                                    'terlambat'   => 'Terlambat',
                                    'sakit'       => 'Sakit',
                                    'izin'        => 'Izin',
                                    'tidak_hadir' => 'Alpa',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $presences->firstItem() + $loop->index }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-indigo-700 dark:text-indigo-300">
                                                {{ strtoupper(substr($p->user->name ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $p->user->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $p->user->teacher->nip ?? $p->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($p->presence_date)->isoFormat('dddd, D MMM YYYY') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$p->status] ?? '' }}">
                                        {{ $statusLabels[$p->status] ?? $p->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $p->check_in_time ? \Carbon\Carbon::parse($p->check_in_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $p->check_out_time ? \Carbon\Carbon::parse($p->check_out_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    @if($p->late_minutes > 0)
                                        <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $p->late_minutes }} menit</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                    {{ $p->notes ?: '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('admin.presences.crud.show', $p) }}"
                                           title="Detail"
                                           class="p-1.5 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.presences.crud.edit', $p) }}"
                                           title="Edit"
                                           class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/40 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.presences.crud.destroy', $p) }}"
                                              onsubmit="return confirm('Yakin hapus data absensi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center">
                                    <svg class="mx-auto w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data absensi ditemukan.</p>
                                    <a href="{{ route('admin.presences.crud.create') }}"
                                       class="mt-3 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Absensi Baru
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($presences->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $presences->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>
