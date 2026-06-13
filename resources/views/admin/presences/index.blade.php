<x-admin-layout>

    <div class="page-heading mb-4">

        <div class="page-heading-copy">

            <span class="page-icon">
                <i class="bi bi-card-checklist"></i>
            </span>

            <div>

                <p class="eyebrow mb-1">
                    Rekap Kehadiran
                </p>

                <h1 class="h3 mb-1">
                    Rekap Absensi Guru
                </h1>

                <p class="text-muted mb-0">
                    Bulan
                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    {{ $year }}
                </p>

            </div>

        </div>

        <div class="heading-actions">

            <a href="{{ route('admin.presences.export', [
                'month' => $month,
                'year' => $year,
            ]) }}"
                class="btn btn-success">

                <i class="bi bi-download"></i>
                Export CSV

            </a>

        </div>

    </div>

    <section class="panel mb-4">

        <div class="panel-header">

            <h2 class="h5 mb-0">
                Filter Periode
            </h2>

        </div>

        <div class="p-4">

            <form method="GET" action="{{ route('admin.presences') }}" class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label class="form-label">
                        Bulan
                    </label>

                    <select name="month" class="form-select">

                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>

                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}

                            </option>
                        @endfor

                    </select>

                </div>

                <div class="col-md-5">

                    <label class="form-label">
                        Tahun
                    </label>

                    <select name="year" class="form-select">

                        @for ($y = 2024; $y <= 2028; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>
                        @endfor

                    </select>

                </div>

                <div class="col-md-2">

                    <button type="submit" class="btn btn-primary w-100">

                        Filter

                    </button>

                </div>

            </form>

        </div>

    </section>

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="metric-card metric-success">

                <div class="metric-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="metric-copy">
                    <span class="metric-label">
                        Total Hadir
                    </span>

                    <strong class="metric-value">
                        {{ $summary->sum('summary.hadir') }}
                    </strong>
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="metric-card metric-warning">

                <div class="metric-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="metric-copy">
                    <span class="metric-label">
                        Terlambat
                    </span>

                    <strong class="metric-value">
                        {{ $summary->sum('summary.terlambat') }}
                    </strong>
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="metric-card metric-danger">

                <div class="metric-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div class="metric-copy">
                    <span class="metric-label">
                        Tidak Hadir
                    </span>

                    <strong class="metric-value">
                        {{ $summary->sum('summary.tidak_hadir') }}
                    </strong>
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="metric-card metric-primary">

                <div class="metric-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <div class="metric-copy">
                    <span class="metric-label">
                        Rata-rata Kehadiran
                    </span>

                    <strong class="metric-value">
                        {{ $summary->count() > 0 ? number_format($summary->avg('percentage'), 1) : 0 }}%
                    </strong>
                </div>

            </div>

        </div>

    </div>

    <section class="panel">

        <div class="panel-header">

            <div>

                <h2 class="h5 mb-1">
                    Rekap Guru
                </h2>

                <p class="text-muted mb-0">
                    Ringkasan absensi seluruh guru.
                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>
                        <th>Guru</th>
                        <th>Hadir</th>
                        <th>Terlambat</th>
                        <th>Tidak Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Total</th>
                        <th>Persentase</th>
                        <th>Jam Kerja</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($summary as $item)
                        <tr>

                            <td>

                                <div class="fw-semibold">
                                    {{ $item['teacher']['name'] }}
                                </div>

                                <small class="text-muted">
                                    {{ $item['teacher']['email'] }}
                                </small>

                                @if ($item['teacher']['nip'])
                                    <div class="small text-muted">
                                        NIP:
                                        {{ $item['teacher']['nip'] }}
                                    </div>
                                @endif

                            </td>

                            <td>
                                <span class="badge text-bg-success">
                                    {{ $item['summary']['hadir'] ?? 0 }}
                                </span>
                            </td>

                            <td>
                                <span class="badge text-bg-warning">
                                    {{ $item['summary']['terlambat'] ?? 0 }}
                                </span>
                            </td>

                            <td>
                                <span class="badge text-bg-danger">
                                    {{ $item['summary']['tidak_hadir'] ?? 0 }}
                                </span>
                            </td>

                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $item['summary']['sakit'] ?? 0 }}
                                </span>
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ $item['summary']['izin'] ?? 0 }}
                                </span>
                            </td>

                            <td class="fw-semibold">
                                {{ $item['summary']['total'] ?? 0 }}
                            </td>

                            <td style="min-width:180px">

                                <div class="progress mb-1" style="height:8px;">

                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $item['percentage'] }}%">

                                    </div>

                                </div>

                                <small>
                                    {{ number_format($item['percentage'], 1) }}%
                                </small>

                            </td>

                            <td>
                                {{ number_format($item['work_hours'], 1) }}
                                jam
                            </td>

                            <td>

                                <a href="{{ route('teacher.detail', [
                                    'user' => $item['teacher']['id'],
                                    'month' => $month,
                                    'year' => $year,
                                ]) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="text-center py-4 text-muted">

                                Tidak ada data absensi untuk periode ini.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</x-admin-layout>
```
