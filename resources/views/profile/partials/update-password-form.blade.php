<section>

    <div class="mb-4">
        <h4 class="fw-semibold">
            Update Password
        </h4>

        <p class="text-muted mb-0">
            Gunakan password yang kuat dan aman untuk melindungi akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                Password Saat Ini
            </label>

            <input type="password" name="current_password" id="update_password_current_password" class="form-control">

            @if ($errors->updatePassword->has('current_password'))
                <div class="text-danger mt-2">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                Password Baru
            </label>

            <input type="password" name="password" id="update_password_password" class="form-control">

            @if ($errors->updatePassword->has('password'))
                <div class="text-danger mt-2">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label">
                Konfirmasi Password
            </label>

            <input type="password" name="password_confirmation" id="update_password_password_confirmation"
                class="form-control">

            @if ($errors->updatePassword->has('password_confirmation'))
                <div class="text-danger mt-2">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">

            <button type="submit" class="btn btn-primary">
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success">
                    Password berhasil diperbarui.
                </span>
            @endif

        </div>

    </form>

</section>
