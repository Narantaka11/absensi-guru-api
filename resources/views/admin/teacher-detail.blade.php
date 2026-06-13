<x-admin-layout>
    <x-slot name="title">
        Detail Guru: {{ $user->name }}
    </x-slot>

    <div class="page-heading mb-4">

        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-person-badge"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Detail Guru</p>

                <h1 class="h3 mb-1">
                    {{ $user->name }}
                </h1>

                <p class="text-muted mb-0">
                    Informasi absensi, jadwal mengajar, dan penggajian guru.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.teachers') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="row g-4 mb-4">

        <div class="col-xl col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Hadir</small>
                        <h3 class="text-success mb-0">
                            {{ $summary['present'] }}
                        </h3>
                    </div>

                    <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Terlambat</small>
                        <h3 class="text-warning mb-0">
                            {{ $summary['late'] }}
                        </h3>
                    </div>

                    <i class="bi bi-clock-fill fs-1 text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Tidak Hadir</small>
                        <h3 class="text-danger mb-0">
                            {{ $summary['absent'] }}
                        </h3>
                    </div>

                    <i class="bi bi-x-circle-fill fs-1 text-danger"></i>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Sakit</small>
                        <h3 class="text-info mb-0">
                            {{ $summary['sick'] }}
                        </h3>
                    </div>

                    <i class="bi bi-heart-pulse-fill fs-1 text-info"></i>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Izin</small>
                        <h3 class="text-secondary mb-0">
                            {{ $summary['permission'] }}
                        </h3>
                    </div>

                    <i class="bi bi-envelope-check-fill fs-1 text-secondary"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- PENGGAJIAN --}}
    @if ($salary)
        <div class="card mb-4">
            <div class="card-body">

                <h4 class="mb-4">
                    <i class="bi bi-cash-stack me-2"></i>
                    Data Penggajian
                </h4>

                <div class="row g-4">

                    <div class="col-md-6">
                        <small class="text-muted">
                            Gaji Pokok
                        </small>

                        <h5>
                            Rp {{ number_format($salary->base_salary, 0, ',', '.') }}
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">
                            Gaji Total
                        </small>

                        <h5 class="text-success">
                            Rp {{ number_format($salary->total_salary, 0, ',', '.') }}
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">
                            Potongan Absensi
                        </small>

                        <h5 class="text-danger">
                            Rp {{ number_format($salary->deduction_for_absence, 0, ',', '.') }}
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">
                            Potongan Keterlambatan
                        </small>

                        <h5 class="text-warning">
                            Rp {{ number_format($salary->deduction_for_late, 0, ',', '.') }}
                        </h5>
                    </div>

                </div>

                <div class="alert alert-info mt-4 mb-0">
                    Status:
                    <strong>{{ ucfirst($salary->status) }}</strong>
                </div>

            </div>
        </div>
    @endif

    {{-- JADWAL --}}
    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4 class="mb-0">
                    <i class="bi bi-calendar3 me-2"></i>
                    Jadwal Mengajar
                </h4>

                <small class="text-muted">
                    Kelola jadwal guru
                </small>

            </div>

            <div class="table-responsive mb-4">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th width="180" class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($schedules as $schedule)
                            <tr>

                                <td>{{ $schedule->day_name }}</td>

                                <td>{{ substr($schedule->start_time, 0, 5) }}</td>

                                <td>{{ substr($schedule->end_time, 0, 5) }}</td>

                                <td>{{ $schedule->subject }}</td>

                                <td>{{ $schedule->class_name }}</td>

                                <td>
                                    @if ($schedule->is_active)
                                        <span class="badge bg-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('admin.teacher.schedules.edit', ['user' => $user, 'schedule' => $schedule]) }}"
                                        class="btn btn-sm btn-outline-warning">

                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.teacher.schedules.destroy', ['user' => $user, 'schedule' => $schedule]) }}"
                                        method="POST" class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus jadwal ini?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada jadwal.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <hr>

            <h5 class="mb-3">
                Tambah Jadwal Baru
            </h5>

            <form action="{{ route('admin.teacher.schedules.store', ['user' => $user]) }}" method="POST"
                class="row g-3">

                @csrf

                <div class="col-md-6">
                    <label class="form-label">Hari</label>

                    <select name="day_of_week" class="form-select">

                        @foreach (\App\Models\Schedule::DAY_NAMES as $key => $day)
                            <option value="{{ $key }}">
                                {{ $day }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>

                    <input type="text" name="subject" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Mulai</label>

                    <input type="time" name="start_time" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Selesai</label>

                    <input type="time" name="end_time" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelas</label>

                    <input type="text" name="class_name" class="form-control" required>
                </div>

                <div class="col-12">

                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>

                        <label class="form-check-label">
                            Aktifkan jadwal ini
                        </label>

                    </div>

                </div>

                <div class="col-12 text-end">

                    <button type="submit" class="btn btn-primary">

                        Simpan Jadwal

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ABSENSI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-card-checklist me-2"></i>
            Riwayat Absensi
        </h4>

        <a href="{{ route('admin.presences.crud.index') }}" class="btn btn-warning fw-semibold">
            <i class="bi bi-pencil-square me-1"></i>
            Kelola Data Absensi
        </a>
    </div>

    <table class="table table-hover align-middle">

        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Terlambat</th>
                <th>Status</th>
                <th width="180" class="text-center">
                    Aksi
                </th>
            </tr>
        </thead>

        <tbody>

            @forelse($presences as $presence)
                <tr>

                    <td>
                        {{ $presence->presence_date->format('d M Y') }}
                    </td>

                    <td>
                        {{ $presence->check_in_time?->format('H:i') ?? '-' }}
                    </td>

                    <td>
                        {{ $presence->check_out_time?->format('H:i') ?? '-' }}
                    </td>

                    <td>
                        {{ $presence->late_minutes ?: '-' }}
                    </td>

                    <td>
                        @if ($presence->status == 'hadir')
                            <span class="badge bg-success px-3 py-2">
                                Hadir
                            </span>
                        @elseif($presence->status == 'terlambat')
                            <span class="badge bg-warning text-dark px-3 py-2">
                                Terlambat
                            </span>
                        @elseif($presence->status == 'sakit')
                            <span class="badge bg-info px-3 py-2">
                                Sakit
                            </span>
                        @elseif($presence->status == 'izin')
                            <span class="badge bg-primary px-3 py-2">
                                Izin
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2">
                                Tidak Hadir
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('presence.detail', $presence->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>
                            Detail
                        </a>
                    </td>

                    </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Tidak ada data absensi
                    </td>
                </tr>
            @endforelse

        </tbody>

    </table>

    </div>

    <div class="mt-3">
        {{ $presences->links() }}
    </div>

    </div>

    </div>

</x-admin-layout>
```
