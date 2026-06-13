<div class="page-content">
    <div class="row justify-content-center">
        <div class="container-fluid">
            {{-- ================================================================ --}}
            {{-- HEADER --}}
            {{-- ================================================================ --}}
            <div class="container-fluid">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">
                            {{ isset($presence) ? 'Edit Data Absensi' : 'Tambah Data Absensi' }}
                        </h2>
                        <p class="text-secondary mb-0">
                            {{ isset($presence) ? 'Perbarui data kehadiran guru' : 'Kelola data kehadiran guru' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.presences.crud.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </a>

                </div>

                {{-- ================================================================ --}}
                {{-- FORM CARD --}}
                {{-- ================================================================ --}}
                <div class="card shadow-sm border-0 overflow-hidden">
                    <form method="POST"
                        action="{{ $presence ? route('admin.presences.crud.update', $presence) : route('admin.presences.crud.store') }}">
                        @csrf
                        @if (isset($presence))
                            @method('PUT')
                        @endif

                        <div class="card-body p-4 p-sm-5">
                            <div class="row g-4">

                                {{-- ── Guru ── --}}
                                <div class="col-12">
                                    <label for="user_id" class="form-label form-label fw-bold">
                                        Guru <span class="text-danger">*</span>
                                    </label>
                                    <select id="user_id" name="user_id"
                                        class="form-select @error('user_id') is-invalid @enderror">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}"
                                                {{ old('user_id', $presence?->user_id) == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                                @if ($t->teacher?->nip)
                                                    ({{ $t->teacher->nip }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- ── Tanggal ── --}}
                                <div class="col-12 col-sm-6">
                                    <label for="presence_date" class="form-label form-label fw-bold">
                                        Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="presence_date" name="presence_date"
                                        value="{{ old('presence_date', $presence?->presence_date?->format('Y-m-d')) }}"
                                        class="form-select @error('presence_date') is-invalid @enderror">
                                    @error('presence_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- ── Status Kehadiran ── --}}
                                <div class="col-12 col-sm-6">
                                    <label for="status" class="form-label form-label fw-bold">
                                        Status Kehadiran <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" name="status" onchange="handleStatusChange(this.value)"
                                        class="form-select @error('status') is-invalid @enderror">
                                        <option value="">-- Pilih Status --</option>
                                        @foreach ($statuses as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('status', $presence?->status) === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- ── Jam Masuk & Keluar (Wrapper row agar fleksibel disembunyikan) ── --}}
                                <div class="col-12">
                                    <div class="row g-4" id="time-section">
                                        <div class="col-12 col-sm-6">
                                            <label for="check_in_time" class="form-label form-label fw-bold">
                                                Jam Masuk
                                            </label>
                                            <input type="time" id="check_in_time" name="check_in_time"
                                                value="{{ old('check_in_time', $presence?->check_in_time ? \Carbon\Carbon::parse($presence->check_in_time)->format('H:i') : '') }}"
                                                class="form-control @error('check_in_time') is-invalid @enderror">
                                            @error('check_in_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <label for="check_out_time" class="form-label form-label fw-bold">
                                                Jam Keluar
                                            </label>
                                            <input type="time" id="check_out_time" name="check_out_time"
                                                value="{{ old('check_out_time', $presence?->check_out_time ? \Carbon\Carbon::parse($presence->check_out_time)->format('H:i') : '') }}"
                                                class="form-control @error('check_out_time') is-invalid @enderror">
                                            @error('check_out_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- ── Menit Terlambat ── --}}
                                <div class="col-12 d-none" id="late-section">
                                    <label for="late_minutes" class="form-label form-label fw-bold">
                                        Jumlah Menit Terlambat
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="late_minutes" name="late_minutes" min="0"
                                            value="{{ old('late_minutes', $presence?->late_minutes ?? 0) }}"
                                            placeholder="Akan otomatis dihitung dari jam masuk"
                                            class="form-control @error('late_minutes') is-invalid @enderror">
                                        <span class="input-group-text bg-light text-muted small">menit</span>
                                        @error('late_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted d-block mt-1">Otomatis dihitung dari jam
                                        masuk
                                        jika jam masuk diisi.</small>
                                </div>

                                {{-- ── Keterangan / Notes ── --}}
                                <div class="col-12">
                                    <label for="notes" class="form-label form-label fw-bold">
                                        Keterangan
                                    </label>
                                    <textarea id="notes" name="notes" rows="3"
                                        placeholder="Contoh: Surat dokter terlampir, keperluan keluarga, dll."
                                        class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $presence?->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── Footer Buttons ── --}}
                        <div class="card-footer border-secondary bg-dark">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.presences.crud.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    {{ isset($presence) ? 'Update Absensi' : 'Simpan Absensi' }}
                                </button>
                            </div>
                        </div>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- SCRIPT: Toggle menggunakan utilitas Bootstrap 'd-none' --}}
{{-- ================================================================ --}}
<script>
    function handleStatusChange(status) {
        const timeSection = document.getElementById('time-section');
        const lateSection = document.getElementById('late-section');

        // Status tanpa jam (sakit, izin, alpa) — sembunyikan jam & menit terlambat
        const noTimeStatuses = ['sakit', 'izin', 'tidak_hadir'];

        if (noTimeStatuses.includes(status)) {
            timeSection.classList.add('d-none');
            lateSection.classList.add('d-none');
        } else {
            timeSection.classList.remove('d-none');

            if (status === 'terlambat') {
                lateSection.classList.remove('d-none');
            } else {
                lateSection.classList.add('d-none');
            }
        }
    }

    // Jalankan saat load awal (terutama untuk mode Edit Data)
    document.addEventListener('DOMContentLoaded', function() {
        const statusEl = document.getElementById('status');
        if (statusEl && statusEl.value) {
            handleStatusChange(statusEl.value);
        }
    });
</script>
