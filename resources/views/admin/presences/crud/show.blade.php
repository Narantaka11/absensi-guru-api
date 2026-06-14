<x-admin-layout>
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- ================================================================ --}}
        {{-- HEADER --}}
        {{-- ================================================================ --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.presences.crud.index') }}"
               class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Absensi</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Informasi lengkap data kehadiran guru.</p>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- CARD DETAIL --}}
        {{-- ================================================================ --}}
        @php
            $statusColors = [
                'hadir'       => 'bg-green-100 text-green-800 ring-green-200',
                'terlambat'   => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
                'sakit'       => 'bg-blue-100 text-blue-800 ring-blue-200',
                'izin'        => 'bg-purple-100 text-purple-800 ring-purple-200',
                'tidak_hadir' => 'bg-red-100 text-red-800 ring-red-200',
            ];
            $statusLabels = [
                'hadir'       => 'Hadir (On Time)',
                'terlambat'   => 'Terlambat',
                'sakit'       => 'Sakit',
                'izin'        => 'Izin',
                'tidak_hadir' => 'Alpa (Tidak Hadir)',
            ];
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Status Banner --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ring-1 {{ $statusColors[$presence->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$presence->status] ?? $presence->status }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.presences.crud.edit', $presence) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.presences.crud.destroy', $presence) }}"
                          onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Guru --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                        <span class="text-xl font-bold text-indigo-700 dark:text-indigo-300">
                            {{ strtoupper(substr($presence->user->name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $presence->user->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $presence->user->email ?? '-' }}</p>
                        @if($presence->user->teacher?->nip)
                            <p class="text-xs text-gray-400 mt-0.5">NIP: {{ $presence->user->teacher->nip }}</p>
                        @endif
                        @if($presence->user->teacher?->subject)
                            <p class="text-xs text-gray-400">Mapel: {{ $presence->user->teacher->subject }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail Grid --}}
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Tanggal</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($presence->presence_date)->isoFormat('dddd, D MMMM YYYY') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$presence->status] ?? '' }}">
                                {{ $statusLabels[$presence->status] ?? $presence->status }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Jam Masuk</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $presence->check_in_time ? \Carbon\Carbon::parse($presence->check_in_time)->format('H:i') : '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Jam Keluar</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $presence->check_out_time ? \Carbon\Carbon::parse($presence->check_out_time)->format('H:i') : '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Keterlambatan</dt>
                        <dd class="mt-1 text-sm font-medium {{ $presence->late_minutes > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100' }}">
                            {{ $presence->late_minutes > 0 ? $presence->late_minutes . ' menit' : '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Jam Kerja</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                            @php $wh = $presence->getWorkHours(); @endphp
                            {{ $wh !== null ? number_format($wh, 2) . ' jam' : '-' }}
                        </dd>
                    </div>

                    @if($presence->notes)
                        <div class="col-span-full">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Keterangan</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                {{ $presence->notes }}
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Dibuat</dt>
                        <dd class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $presence->created_at?->isoFormat('D MMM YYYY, HH:mm') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Diperbarui</dt>
                        <dd class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $presence->updated_at?->isoFormat('D MMM YYYY, HH:mm') ?? '-' }}
                        </dd>
                    </div>

                </dl>
            </div>

        </div>

        {{-- Back button --}}
        <div>
            <a href="{{ route('admin.presences.crud.index') }}"
               class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Absensi
            </a>
        </div>
    </div>
</x-admin-layout>
