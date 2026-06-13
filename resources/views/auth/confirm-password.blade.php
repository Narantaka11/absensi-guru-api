<x-guest-layout>

    <div class="alert alert-info">
        Area ini dilindungi.
        Silakan masukkan password Anda untuk melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">

            <label for="password" class="form-label fw-semibold">
                Password
            </label>

            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="text-end">

            <button type="submit" class="btn btn-primary px-4">

                Konfirmasi

            </button>

        </div>

    </form>

</x-guest-layout>
