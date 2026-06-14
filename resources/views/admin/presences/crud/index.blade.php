<x-admin-layout>

    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-calendar-check"></i>
            </span>
            <div>
                <p class="eyebrow mb-1">Manajemen Absensi</p>
                <h1 class="h3 mb-1">Kelola Data Absensi</h1>
                <p class="text-muted mb-0">Monitor dan kelola seluruh data kehadiran guru.</p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.presences.crud.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Absensi
            </a>
        </div>
    </div>
    <section class="panel mb-4">
        <div class="panel-header">
            <h2 class="h5 mb-0">Filter Data Absensi</h2>
        </div>
        <div class="panel-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Guru</label>
                        <select name="user_id" class="form-select">
                            <option value="">
                                Semua Guru
                            </option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ request('user_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">
                                Semua
                            </option>
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-select">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ request('year') }}"
                            placeholder="2026">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="panel mb-4">
        <div class="panel-header">
            <h2 class="h5 mb-0">Data Absensi</h2>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Guru</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Terlambat</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presences as $p)
                        <tr>
                            <td>{{ $presences->firstItem() + $loop->index }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->presence_date }}</td>
                            <td>{{ $p->check_in_time ?? '-' }}</td>
                            <td>{{ $p->check_out_time ?? '-' }}</td>
                            <td>{{ $p->late_minutes ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('admin.presences.crud.show', $p) }}"
                                        class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.presences.crud.edit', $p) }}"
                                        class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.presences.crud.destroy', $p) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                            onclick="return confirm('Hapus data absensi ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $presences->links() }}
        </div>
    </section>

</x-admin-layout>
