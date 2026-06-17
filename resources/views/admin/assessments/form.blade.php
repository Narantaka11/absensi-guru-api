```blade
<x-admin-layout>

    <div class="page-heading mb-4">

        <div class="page-heading-copy">

            <span class="page-icon">
                <i class="bi bi-journal-check"></i>
            </span>

            <div>

                <p class="eyebrow mb-1">
                    Penilaian Guru
                </p>

                <h1 class="h3 mb-1">
                    {{ $assessment ? 'Edit Penilaian Guru' : 'Tambah Penilaian Guru' }}
                </h1>

                <p class="text-muted mb-0">
                    {{ $assessment ? 'Perbarui data penilaian guru.' : 'Tambahkan data penilaian guru baru.' }}
                </p>

            </div>

        </div>

        <div class="heading-actions">

            <a href="{{ route('admin.assessments.index') }}" class="btn btn-outline-secondary">

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

    <section class="panel">

        <div class="panel-header">

            <div>

                <h2 class="h5 mb-1">
                    Form Penilaian
                </h2>

                <p class="text-muted mb-0">
                    Isi seluruh nilai penilaian guru.
                </p>

            </div>

        </div>

        <form method="POST"
            action="{{ $assessment ? route('admin.assessments.update', $assessment) : route('admin.assessments.store') }}">

            @csrf

            @if ($assessment)
                @method('PUT')
            @endif

            <div class="p-4">

                <div class="row g-3">

                    <div class="col-12">

                        <label class="form-label">
                            Guru
                        </label>

                        <select id="user_id" name="user_id" class="form-select">

                            <option value="">
                                -- Pilih Guru --
                            </option>

                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('user_id', $assessment?->user_id) == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->name }}
                                    @if ($teacher->teacher?->nip)
                                        ({{ $teacher->teacher->nip }})
                                    @endif

                                </option>
                            @endforeach

                        </select>

                        @error('user_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Bulan
                        </label>

                        <select id="month" name="month" class="form-select">

                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ old('month', $assessment?->month ?? now()->month) == $i ? 'selected' : '' }}>

                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}

                                </option>
                            @endfor

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tahun
                        </label>

                        <select id="year" name="year" class="form-select">

                            @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}"
                                    {{ old('year', $assessment?->year ?? now()->year) == $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>
                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Kehadiran
                        </label>

                        <input type="number" id="absensi" name="absensi" min="0" max="100" step="0.01"
                            value="{{ old('absensi', $assessment?->absensi ?? 0) }}" class="form-control"
                            oninput="recalculateTotal()">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Disiplin
                        </label>

                        <input type="number" id="disiplin" name="disiplin" min="0" max="100"
                            step="0.01" value="{{ old('disiplin', $assessment?->disiplin ?? 0) }}"
                            class="form-control" oninput="recalculateTotal()">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Keterampilan
                        </label>

                        <input type="number" id="keterampilan" name="keterampilan" min="0" max="100"
                            step="0.01" value="{{ old('keterampilan', $assessment?->keterampilan ?? 0) }}"
                            class="form-control" oninput="recalculateTotal()">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Produktivitas
                        </label>

                        <input type="number" id="produktivitas" name="produktivitas" min="0" max="100"
                            step="0.01" value="{{ old('produktivitas', $assessment?->produktivitas ?? 0) }}"
                            class="form-control" oninput="recalculateTotal()">

                    </div>

                    <div class="col-12">

                        <label class="form-label">
                            Total (Rata-rata)
                        </label>

                        <input type="text" id="total" name="total" readonly
                            value="{{ old('total', $assessment?->total ?? 0) }}" class="form-control bg-light">

                    </div>

                </div>

            </div>

            <div class="panel-footer p-4 border-top d-flex justify-content-between">

                <a href="{{ route('admin.assessments.index') }}" class="btn btn-outline-secondary">

                    Batal

                </a>

                <button type="submit" class="btn btn-primary">

                    {{ $assessment ? 'Simpan Perubahan' : 'Simpan Penilaian' }}

                </button>

            </div>

        </form>

    </section>

    <script>
        function recalculateTotal() {
            const fields = [
                'absensi',
                'disiplin',
                'keterampilan',
                'produktivitas'
            ];

            let sum = 0;

            fields.forEach(id => {
                sum += parseFloat(
                    document.getElementById(id).value || 0
                );
            });

            document.getElementById('total').value =
                (sum / 4).toFixed(2);
        }

        document.addEventListener(
            'DOMContentLoaded',
            recalculateTotal
        );
    </script>

</x-admin-layout>
```
