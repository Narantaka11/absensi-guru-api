<div class="page-heading mb-4">
    <div class="page-heading-copy">
        <span class="page-icon">
            <i class="bi bi-person-plus"></i>
        </span>

        <div>
            <p class="eyebrow mb-1">Master Data</p>

            <h1 class="h3 mb-1">
                {{ isset($teacher) ? 'Edit Guru' : 'Tambah Guru' }}
            </h1>

            <p class="text-muted mb-0">
                {{ isset($teacher) ? 'Perbarui data guru yang terdaftar.' : 'Tambahkan data guru baru ke dalam sistem.' }}
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

<section class="panel">

    <div class="panel-header">
        <div>
            <h2 class="h5 mb-1 section-title">
                <i class="bi bi-person-vcard"></i>
                <span>Form Guru</span>
            </h2>

            <p class="text-muted mb-0">
                Lengkapi informasi guru di bawah ini.
            </p>
        </div>
    </div>

    <form method="POST"
        action="{{ isset($teacher) ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}">

        @csrf

        @isset($teacher)
            @method('PUT')
        @endisset

        <div class="p-4">

            <div class="row g-3">

                {{-- Nama --}}
                <div class="col-md-6">
                    <label class="form-label">Nama Guru</label>

                    <input type="text" name="name" value="{{ old('name', $teacher?->user?->name) }}"
                        class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label class="form-label">Email</label>

                    <input type="email" name="email" value="{{ old('email', $teacher?->user?->email) }}"
                        class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password hanya saat create --}}
                @if (!isset($teacher))
                    <div class="col-md-6">
                        <label class="form-label">Password</label>

                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                {{-- NIP --}}
                <div class="col-md-6">
                    <label class="form-label">NIP</label>

                    <input type="text" name="nip" value="{{ old('nip', $teacher?->nip) }}"
                        class="form-control @error('nip') is-invalid @enderror">

                    @error('nip')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>

                    <input type="text" name="phone" value="{{ old('phone', $teacher?->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror">

                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Mata Pelajaran --}}
                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>

                    <input type="text" name="subject" value="{{ old('subject', $teacher?->subject) }}"
                        class="form-control @error('subject') is-invalid @enderror">

                    @error('subject')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Gaji Pokok --}}
                <div class="col-md-6">
                    <label class="form-label">Gaji Pokok</label>

                    <input type="number" name="base_salary" value="{{ old('base_salary', $teacher?->base_salary) }}"
                        class="form-control @error('base_salary') is-invalid @enderror">

                    @error('base_salary')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="col-12">
                    <label class="form-label">Alamat</label>

                    <textarea rows="4" name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $teacher?->address) }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="p-3 border-top">
            <div class="d-flex justify-content-between">

                <a href="{{ route('admin.teachers') }}" class="btn btn-outline-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">

                    <i class="bi bi-save"></i>

                    {{ isset($teacher) ? ' Update Guru' : ' Simpan Guru' }}

                </button>

            </div>
        </div>

    </form>

</section>
