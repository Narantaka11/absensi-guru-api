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
