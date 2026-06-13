<x-admin-layout>

    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-people"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Master Data</p>
                <h1 class="h3 mb-1">Daftar Guru</h1>
                <p class="text-muted mb-0">
                    Kelola data guru yang terdaftar dalam sistem.
                </p>
            </div>
        </div>

        <div class="heading-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
                Dashboard
            </a>
        </div>
    </div>

    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Data Guru</span>
                </h2>

                <p class="text-muted mb-0">
                    Daftar seluruh guru yang terdaftar.
                </p>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIP</th>
                        <th>Mata Pelajaran</th>
                        <th>Lokasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($teachers as $teacher)
                        <tr>

                            <td>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $teacher->name }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $teacher->email }}
                            </td>

                            <td>
                                {{ $teacher->teacher?->nip ?? '-' }}
                            </td>

                            <td>
                                {{ $teacher->teacher?->subject ?? '-' }}
                            </td>

                            <td>
                                {{ $teacher->teacher?->location?->name ?? '-' }}
                            </td>

                            <td class="text-center">

                                <a href="{{ route('teacher.detail', ['user' => $teacher->id]) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>
                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada data guru
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-3 border-top">
            {{ $teachers->links() }}
        </div>

    </section>

</x-admin-layout>
```
