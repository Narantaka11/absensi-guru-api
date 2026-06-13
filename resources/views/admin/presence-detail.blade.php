<x-admin-layout>
    <x-slot name="title">
        Detail Absensi {{ $presence->user->name }}
    </x-slot>

    <div class="page-heading mb-4">

        <div class="page-heading-copy">

            <span class="page-icon">
                <i class="bi bi-card-checklist"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">Detail Absensi</p>

                <h1 class="h3 mb-1">
                    {{ $presence->user->name }}
                </h1>

                <p class="text-muted mb-0">
                    Informasi lengkap kehadiran guru.
                </p>
            </div>

        </div>

        <div class="heading-actions">

            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">

                <i class="bi bi-arrow-left"></i>
                Kembali

            </a>

        </div>

    </div>

    <div class="row g-4">

        {{-- INFORMASI DASAR --}}
        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi Dasar
                    </h4>

                    <div class="row g-4">

                        <div class="col-md-3">
                            <small class="text-muted">
                                Nama Guru
                            </small>

                            <h6 class="mb-0">
                                {{ $presence->user->name }}
                            </h6>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">
                                Tanggal
                            </small>

                            <h6 class="mb-0">
                                {{ $presence->presence_date->format('d M Y') }}
                            </h6>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">
                                Status
                            </small>

                            <div class="mt-2">

                                @if ($presence->status == 'hadir')
                                    <span class="badge bg-success">
                                        Hadir
                                    </span>
                                @elseif($presence->status == 'terlambat')
                                    <span class="badge bg-warning text-dark">
                                        Terlambat
                                    </span>
                                @elseif(in_array($presence->status, ['tidak_hadir', 'alpa']))
                                    <span class="badge bg-danger">
                                        Tidak Hadir
                                    </span>
                                @elseif($presence->status == 'sakit')
                                    <span class="badge bg-info">
                                        Sakit
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Izin
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">
                                Radius Sekolah
                            </small>

                            <div class="mt-2">

                                @if ($isWithinRadius)
                                    <span class="badge bg-success">
                                        Valid
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Tidak Valid
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- CHECK IN --}}
        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="mb-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Informasi Check-In
                    </h4>

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Jam Masuk
                            </small>

                            <h2 class="fw-bold mb-2">
                                {{ $presence->check_in_time ? $presence->check_in_time->format('H:i:s') : '-' }}
                            </h2>

                            @if ($presence->late_minutes > 0)
                                <div class="alert alert-warning py-2 mb-0">
                                    Terlambat {{ $presence->late_minutes }} menit
                                </div>
                            @endif

                        </div>

                        <div class="col-md-6">

                            <small class="text-muted">
                                Lokasi GPS
                            </small>

                            @if ($presence->check_in_latitude && $presence->check_in_longitude)
                                <p class="mb-2 font-monospace">

                                    {{ $presence->check_in_latitude }},
                                    {{ $presence->check_in_longitude }}

                                </p>

                                <a href="https://maps.google.com/?q={{ $presence->check_in_latitude }},{{ $presence->check_in_longitude }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-geo-alt"></i>
                                    Google Maps

                                </a>
                            @else
                                <p class="text-muted">
                                    Tidak tersedia
                                </p>
                            @endif

                        </div>

                    </div>

                    @if ($presence->check_in_photo)
                        <hr>

                        <h6 class="mb-3">
                            Foto Check-In
                        </h6>

                        <img src="{{ asset('storage/' . $presence->check_in_photo) }}" alt="Check In"
                            class="img-fluid rounded shadow-sm" style="max-width:500px;">
                    @endif

                </div>

            </div>

        </div>

        {{-- CHECK OUT --}}
        @if ($presence->check_out_time)

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <h4 class="mb-4">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Informasi Check-Out
                        </h4>

                        <div class="row g-4">

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Jam Keluar
                                </small>

                                <h2 class="fw-bold mb-2">
                                    {{ $presence->check_out_time->format('H:i:s') }}
                                </h2>

                                @if ($workHours)
                                    <div class="alert alert-info py-2 mb-0">
                                        Jam Kerja :
                                        {{ number_format($workHours, 2) }}
                                        jam
                                    </div>
                                @endif

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Lokasi GPS
                                </small>

                                @if ($presence->check_out_latitude && $presence->check_out_longitude)
                                    <p class="mb-2 font-monospace">

                                        {{ $presence->check_out_latitude }},
                                        {{ $presence->check_out_longitude }}

                                    </p>

                                    <a href="https://maps.google.com/?q={{ $presence->check_out_latitude }},{{ $presence->check_out_longitude }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-geo-alt"></i>
                                        Google Maps

                                    </a>
                                @else
                                    <p class="text-muted">
                                        Tidak tersedia
                                    </p>
                                @endif

                            </div>

                        </div>

                        @if ($presence->check_out_photo)
                            <hr>

                            <h6 class="mb-3">
                                Foto Check-Out
                            </h6>

                            <img src="{{ asset('storage/' . $presence->check_out_photo) }}" alt="Check Out"
                                class="img-fluid rounded shadow-sm" style="max-width:500px;">
                        @endif

                    </div>

                </div>

            </div>

        @endif

        {{-- CATATAN --}}
        @if ($presence->notes)
            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <h4 class="mb-3">
                            <i class="bi bi-chat-left-text me-2"></i>
                            Catatan
                        </h4>

                        <p class="mb-0">
                            {{ $presence->notes }}
                        </p>

                    </div>

                </div>

            </div>
        @endif

    </div>

</x-admin-layout>
```
