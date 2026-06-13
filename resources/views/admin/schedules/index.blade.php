<x-admin-layout>

    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-calendar3"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Manajemen Jadwal</p>
                <h1 class="h3 mb-1">Jadwal Mengajar Guru</h1>
                <p class="text-muted mb-0">
                    Daftar seluruh jadwal mengajar guru.
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
                    <i class="bi bi-calendar-week"></i>
                    <span>Jadwal Mengajar</span>
                </h2>

                <p class="text-muted mb-0">
                    Data jadwal mengajar seluruh guru.
                </p>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Guru</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Jam Mulai</th>
                        <th class="text-center">Jam Selesai</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($schedules as $schedule)
                        <tr>

                            <td>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $schedule->user->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $schedule->user->email }}
                                    </small>
                                </div>
                            </td>

                            <td class="text-center">
                                {{ \App\Models\Schedule::DAY_NAMES[$schedule->day_of_week] ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $schedule->start_time }}
                            </td>

                            <td class="text-center">
                                {{ $schedule->end_time }}
                            </td>

                            <td>
                                {{ $schedule->subject }}
                            </td>

                            <td>
                                {{ $schedule->class_name }}
                            </td>

                            <td class="text-center">

                                @if ($schedule->is_active)
                                    <span class="badge text-bg-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary">
                                        Tidak Aktif
                                    </span>
                                @endif

                            </td>

                            <td class="text-center">

                                <a href="{{ route('teacher.detail', ['user' => $schedule->user->id]) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>
                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-4 text-muted">

                                Tidak ada jadwal mengajar

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-3 border-top">

            {{ $schedules->links() }}

        </div>

    </section>

</x-admin-layout>
```
