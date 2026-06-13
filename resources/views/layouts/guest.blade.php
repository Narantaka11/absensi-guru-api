<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Guru</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/style.css') }}">
</head>

<body class="auth-body">
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle
        aria-label="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon></i>
    </button>
    <main class="auth-page">
        <section class="auth-card">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah" style="max-width:220px">
            </div>
            {{ $slot }}
        </section>
    </main>
    <script src="{{ asset('vendor/adminhmd/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminhmd/assets/js/main.js') }}"></script>
</body>

</html>
