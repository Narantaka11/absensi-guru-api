<x-guest-layout>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <p class="eyebrow mb-1">
                Secure Access
            </p>
            <h1 class="h3 mb-1">
                Login
            </h1>
            <p class="text-muted mb-0">
                Silakan login menggunakan akun Anda.
            </p>
        </div>
        <div class="mb-3">
            <label class="form-label">
                Email Address
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label class="form-label">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small fw-semibold">
                        Forgot?
                    </a>
                @endif
            </div>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
            <label class="form-check-label" for="rememberMe">
                Remember me
            </label>
        </div>
        <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-box-arrow-in-right"></i>
            Login
        </button>
    </form>

</x-guest-layout>
