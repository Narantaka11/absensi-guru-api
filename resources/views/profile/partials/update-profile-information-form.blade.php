<section>

    <div class="mb-4">
        <h4 class="fw-semibold">
            Informasi Profil
        </h4>

        <p class="text-muted mb-0">
            Perbarui informasi akun dan alamat email Anda.
        </p>
    </div>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}">

        @csrf
        @method('PATCH')

        <div class="mb-3">

            <label for="name" class="form-label">
                Nama
            </label>

            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                class="form-control" required autofocus>

            @error('name')
                <div class="text-danger mt-2">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-4">

            <label for="email" class="form-label">
                Email
            </label>

            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                class="form-control" required>

            @error('email')
                <div class="text-danger mt-2">
                    {{ $message }}
                </div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())

                <div class="mt-3">

                    <p class="text-muted">

                        Email Anda belum diverifikasi.

                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none">

                            Kirim ulang email verifikasi

                        </button>

                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0">
                            Link verifikasi telah dikirim ulang.
                        </div>
                    @endif

                </div>

            @endif

        </div>

        <div class="d-flex align-items-center gap-3">

            <button type="submit" class="btn btn-primary">

                Simpan Perubahan

            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success">
                    Data berhasil diperbarui.
                </span>
            @endif

        </div>

    </form>

</section>
