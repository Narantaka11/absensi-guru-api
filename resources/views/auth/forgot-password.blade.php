<x-guest-layout>

    <div class="alert alert-info">
        Lupa password? Masukkan alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password.
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">

            <label for="email" class="form-label fw-semibold">
                Email
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="text-end">

            <button type="submit" class="btn btn-primary">

                Kirim Link Reset Password

            </button>

        </div>

    </form>

</x-guest-layout>
