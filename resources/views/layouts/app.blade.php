<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Panel</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/style.css') }}">

    @vite(['resources/js/app.js'])

    <style>
        body {
            background: #f4f6fb;
            color: #1f2937;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
            color: #fff;
            padding: 24px 18px;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 24px;
        }

        .sidebar .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            margin-right: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 6px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .main-shell {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-area {
            padding: 24px;
            flex: 1;
        }

        .card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex min-vh-100">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark"><i class="bi bi-calendar2-check"></i></span>
                <span>Absensi Guru</span>
            </a>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                @if(auth()->user()?->role === \App\Models\User::ROLE_ADMIN)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-columns-gap me-2"></i>Admin Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.teachers') ? 'active' : '' }}" href="{{ route('admin.teachers') }}">
                            <i class="bi bi-people me-2"></i>Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}" href="{{ route('admin.schedules') }}">
                            <i class="bi bi-calendar3 me-2"></i>Jadwal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.assessments.index') ? 'active' : '' }}" href="{{ route('admin.assessments.index') }}">
                            <i class="bi bi-journal-check me-2"></i>Penilaian
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-circle me-2"></i>Profil
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <div class="main-shell">
            <header class="topbar">
                <div>
                    <p class="text-muted mb-1">Selamat datang</p>
                    <h5 class="fw-semibold mb-0">{{ auth()->user()?->name ?? 'Pengguna' }}</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success">Online</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">Profil</a>
                </div>
            </header>

            <main class="content-area">
                @isset($header)
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>{{ $header }}</div>
                        <span class="text-muted small">Template Bootstrap aktif</span>
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="{{ asset('vendor/adminhmd/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminhmd/assets/js/main.js') }}"></script>
</body>

</html>
