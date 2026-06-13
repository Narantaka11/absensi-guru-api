<x-admin-layout>

    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3>Detail Absensi</h3>
                <p class="text-subtitle text-muted mb-0">
                    Informasi lengkap data kehadiran guru
                </p>
            </div>
        </div>
    </div>

    <section class="section">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Data Kehadiran
                </h4>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.presences.crud.edit', $presence) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>

                    <form action="{{ route('admin.presences.crud.destroy', $presence) }}" method="POST"
                        onsubmit="return confirm('Hapus data absensi ini?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">

                            <i class="bi bi-trash"></i>
                            Hapus

                        </button>

                    </form>

                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Guru</label>
                        <div>{{ $presence->user->name ?? '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">NIP</label>
                        <div>{{ $presence->user->teacher->nip ?? '-' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <div>
                            {{ \Carbon\Carbon::parse($presence->presence_date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div>

                            @php
                                $badge = match ($presence->status) {
                                    'hadir' => 'success',
                                    'terlambat' => 'warning',
                                    'sakit' => 'info',
                                    'izin' => 'primary',
                                    'tidak_hadir' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $presence->status)) }}
                            </span>

                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jam Masuk</label>
                        <div>
                            {{ $presence->check_in_time ? \Carbon\Carbon::parse($presence->check_in_time)->format('H:i') : '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jam Keluar</label>
                        <div>
                            {{ $presence->check_out_time ? \Carbon\Carbon::parse($presence->check_out_time)->format('H:i') : '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Terlambat</label>
                        <div>
                            {{ $presence->late_minutes ?? 0 }} menit
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jam Kerja</label>
                        <div>
                            {{ $presence->getWorkHours() ?? '-' }}
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Keterangan</label>

                        <div class="border rounded p-3">

                            {{ $presence->notes ?: '-' }}

                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Dibuat</label>
                        <div>
                            {{ $presence->created_at }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Diperbarui</label>
                        <div>
                            {{ $presence->updated_at }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

</x-admin-layout>
