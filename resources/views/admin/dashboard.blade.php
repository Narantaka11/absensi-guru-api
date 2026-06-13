<x-admin-layout>

    <div class="page-heading mb-4">
        <h1 class="mb-2">Dashboard Absensi Guru</h1>
        <p class="text-muted mb-0">
            Monitoring kehadiran dan performa guru
        </p>
    </div>

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Total Guru</small>
                    <h2 class="fw-bold mt-2">
                        {{ count($attendanceSummary) }}
                    </h2>
                    <p class="text-muted mb-0">Guru Aktif</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Rata-rata Kehadiran</small>
                    <h2 class="fw-bold text-primary mt-2">
                        {{ $statistics['average_attendance'] }}%
                    </h2>
                    <p class="text-muted mb-0">Kehadiran Bulanan</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Total Hadir</small>
                    <h2 class="fw-bold text-success mt-2">
                        {{ $statistics['present'] }}
                    </h2>
                    <p class="text-muted mb-0">Kehadiran Tercatat</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Total Terlambat</small>
                    <h2 class="fw-bold text-warning mt-2">
                        {{ $statistics['late'] }}
                    </h2>
                    <p class="text-muted mb-0">Keterlambatan Guru</p>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Rekap Absensi Guru</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>Nama Guru</th>
                            <th>Hadir</th>
                            <th>Terlambat</th>
                            <th>Tidak Hadir</th>
                            <th>Sakit</th>
                            <th>Izin</th>
                            <th>Persentase</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($attendanceSummary as $record)
                            @php
                                $percentage = $record['percentage'];

                                if ($percentage >= 90) {
                                    $badge = 'success';
                                    $status = 'Sangat Baik';
                                } elseif ($percentage >= 75) {
                                    $badge = 'primary';
                                    $status = 'Baik';
                                } else {
                                    $badge = 'danger';
                                    $status = 'Perlu Evaluasi';
                                }
                            @endphp

                            <tr>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $record['teacher']->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $record['teacher']->email }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        {{ $record['present'] }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $record['late'] }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        {{ $record['absent'] }}
                                    </span>
                                </td>

                                <td>{{ $record['sick'] }}</td>

                                <td>{{ $record['permission'] }}</td>

                                <td>
                                    {{ $record['percentage'] }}%
                                </td>

                                <td>
                                    <span class="badge bg-{{ $badge }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('teacher.detail', [
                                        'user' => $record['teacher']->id,
                                        'month' => $currentMonth,
                                        'year' => $currentYear,
                                    ]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    Tidak ada data guru
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-admin-layout>
