<x-admin-layout>

    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-journal-check"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Penilaian Guru</p>
                <h1 class="h3 mb-1">Penilaian Kinerja Guru</h1>
                <p class="text-muted mb-0">
                    Kelola penilaian kinerja guru berdasarkan periode tertentu.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Penilaian
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <section class="panel mb-4">

        <div class="panel-header">
            <h2 class="h5 mb-0">
                Filter Periode
            </h2>
        </div>

        <div class="p-4">

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Bulan</label>

                    <select name="month" class="form-select">

                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>

                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}

                            </option>
                        @endfor

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun</label>

                    <select name="year" class="form-select">

                        @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>
                        @endfor

                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">

                        Filter

                    </button>

                    <a href="{{ route('admin.assessments.index') }}" class="btn btn-outline-secondary">

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </section>

    @if ($unassessedTeachers->count() > 0)

        <section class="panel mb-4">

            <div class="panel-header">

                <div>
                    <h2 class="h5 mb-1">
                        Guru Belum Dinilai
                    </h2>

                    <p class="text-muted mb-0">
                        {{ $unassessedTeachers->count() }}
                        guru belum memiliki penilaian pada periode ini.
                    </p>
                </div>

            </div>

            <div class="p-4">

                <div class="row g-3">

                    @foreach ($unassessedTeachers as $teacher)
                        <div class="col-md-6 col-xl-4">

                            <div class="card h-100">

                                <div class="card-body">

                                    <h6 class="mb-2">
                                        {{ $teacher->name }}
                                    </h6>

                                    <p class="text-muted small mb-1">
                                        {{ $teacher->email }}
                                    </p>

                                    <p class="text-muted small mb-1">
                                        NIP:
                                        {{ $teacher->teacher?->nip ?? '-' }}
                                    </p>

                                    <p class="text-muted small mb-0">
                                        Mapel:
                                        {{ $teacher->teacher?->subject ?? '-' }}
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </section>

    @endif

    <section class="panel">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1">
                    Data Penilaian
                </h2>

                <p class="text-muted mb-0">
                    Daftar penilaian guru pada periode terpilih.
                </p>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Nama Guru</th>
                        <th>Periode</th>
                        <th class="text-end">Absensi</th>
                        <th class="text-end">Disiplin</th>
                        <th class="text-end">Keterampilan</th>
                        <th class="text-end">Produktivitas</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($assessments as $assessment)
                        <tr>

                            <td>
                                {{ $assessment->user->name }}
                            </td>

                            <td>
                                {{ date('F Y', mktime(0, 0, 0, $assessment->month, 1, $assessment->year)) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($assessment->absensi, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($assessment->disiplin, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($assessment->keterampilan, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($assessment->produktivitas, 2) }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ number_format($assessment->total, 2) }}
                            </td>

                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('admin.assessments.edit', $assessment) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form method="POST" action="{{ route('admin.assessments.destroy', $assessment) }}"
                                        onsubmit="return confirm('Hapus penilaian ini?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-4 text-muted">

                                Belum ada penilaian untuk periode
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                {{ $year }}

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-3 border-top">
            {{ $assessments->links() }}
        </div>

    </section>

</x-admin-layout>
```
