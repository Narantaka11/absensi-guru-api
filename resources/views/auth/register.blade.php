<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- NAMA --}}
        <div class="mb-3">

            <label for="name" class="form-label fw-semibold">
                Nama Lengkap
            </label>

            <input id="name" type="text" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name">

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- EMAIL --}}
        <div class="mb-3">

            <label for="email" class="form-label fw-semibold">
                Email
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autocomplete="username">

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">

            <label for="password" class="form-label fw-semibold">
                Password
            </label>

            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="mb-4">

            <label for="password_confirmation" class="form-label fw-semibold">
                Konfirmasi Password
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

        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('login') }}" class="text-decoration-none">
                Sudah punya akun?
            </a>

            <button type="submit" class="btn btn-primary px-4">
                Register
            </button>

        </div>

    </form>

</x-guest-layout>
