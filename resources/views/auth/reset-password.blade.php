<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">

            <label for="email" class="form-label fw-semibold">
                Email
            </label>

            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="password" class="form-label fw-semibold">
                Password Baru
            </label>

            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-4">

            <label for="password_confirmation" class="form-label fw-semibold">
                Konfirmasi Password Baru
            </label>

            <input id="password_confirmation" type="password" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror" required
                autocomplete="new-password">

            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="text-end">

            <button type="submit" class="btn btn-primary">

                Reset Password

            </button>

        </div>

    </form>

</x-guest-layout>
