<x-admin-layout>
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- ================================================================ --}}
        {{-- HEADER --}}
        {{-- ================================================================ --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.presences.crud.index') }}"
               class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $presence ? 'Edit Data Absensi' : 'Tambah Data Absensi' }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ $presence ? 'Perbarui informasi kehadiran guru.' : 'Isi formulir untuk menambah data kehadiran guru.' }}
                </p>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- FORM --}}
        {{-- ================================================================ --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <form method="POST"
                  action="{{ $presence
                      ? route('admin.presences.crud.update', $presence)
                      : route('admin.presences.crud.store') }}">
                @csrf
                @if($presence) @method('PUT') @endif

                <div class="p-6 space-y-5">

                    {{-- ── Guru ── --}}
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Guru <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" name="user_id"
                                class="w-full rounded-lg border @error('user_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                       px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}"
                                    {{ old('user_id', $presence?->user_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                    @if($t->teacher?->nip) ({{ $t->teacher->nip }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Tanggal & Status ── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <label for="presence_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="presence_date" name="presence_date"
                                   value="{{ old('presence_date', $presence?->presence_date?->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border @error('presence_date') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                          px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('presence_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Status Kehadiran <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                    onchange="handleStatusChange(this.value)"
                                    class="w-full rounded-lg border @error('status') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                           px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statuses as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('status', $presence?->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Jam Masuk & Jam Keluar ── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="time-section">
                        <div>
                            <label for="check_in_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Jam Masuk
                            </label>
                            <input type="time" id="check_in_time" name="check_in_time"
                                   value="{{ old('check_in_time', $presence?->check_in_time ? \Carbon\Carbon::parse($presence->check_in_time)->format('H:i') : '') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                          px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('check_in_time')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="check_out_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Jam Keluar
                            </label>
                            <input type="time" id="check_out_time" name="check_out_time"
                                   value="{{ old('check_out_time', $presence?->check_out_time ? \Carbon\Carbon::parse($presence->check_out_time)->format('H:i') : '') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                          px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('check_out_time')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Menit Terlambat (hanya tampil bila terlambat) ── --}}
                    <div id="late-section" class="hidden">
                        <label for="late_minutes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Jumlah Menit Terlambat
                        </label>
                        <div class="relative">
                            <input type="number" id="late_minutes" name="late_minutes" min="0"
                                   value="{{ old('late_minutes', $presence?->late_minutes ?? 0) }}"
                                   placeholder="Akan otomatis dihitung dari jam masuk"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                          pl-4 pr-16 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">menit</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Otomatis dihitung dari jam masuk jika jam masuk diisi.</p>
                        @error('late_minutes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Keterangan / Notes ── --}}
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Keterangan
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  placeholder="Contoh: Surat dokter terlampir, keperluan keluarga, dll."
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                         bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                                         px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('notes', $presence?->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- ── Footer buttons ── --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.presences.crud.index') }}"
                       class="px-5 py-2.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                        {{ $presence ? 'Simpan Perubahan' : 'Tambah Absensi' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SCRIPT: Toggle sections berdasarkan status --}}
    {{-- ================================================================ --}}
    <script>
        function handleStatusChange(status) {
            const timeSection = document.getElementById('time-section');
            const lateSection = document.getElementById('late-section');

            // Status tanpa jam (sakit, izin, alpa) — sembunyikan jam & menit terlambat
            const noTimeStatuses = ['sakit', 'izin', 'tidak_hadir'];

            if (noTimeStatuses.includes(status)) {
                timeSection.classList.add('hidden');
                lateSection.classList.add('hidden');
            } else {
                timeSection.classList.remove('hidden');
                lateSection.classList.toggle('hidden', status !== 'terlambat');
            }
        }

        // Jalankan saat load (untuk halaman edit)
        document.addEventListener('DOMContentLoaded', function () {
            const statusEl = document.getElementById('status');
            if (statusEl.value) handleStatusChange(statusEl.value);
        });
    </script>
</x-admin-layout>
