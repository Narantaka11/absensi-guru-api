<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminhmd/assets/css/style.css') }}">
    @vite(['resources/js/app.js'])
</head>

<body>
    <div class="admin-shell">

        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar">

            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('admin.dashboard') }}">
                    <span class="brand-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </span>

                    <span class="brand-copy">
                        <span class="brand-title">Absensi Guru</span>
                        <span class="brand-subtitle">Admin Dashboard</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">

                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}"
                    href="{{ route('admin.teachers') }}">
                    <span class="nav-icon">
                        <i class="bi bi-people"></i>
                    </span>
                    <span class="nav-text">Daftar Guru</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}"
                    href="{{ route('admin.schedules') }}">
                    <span class="nav-icon">
                        <i class="bi bi-calendar3"></i>
                    </span>
                    <span class="nav-text">Jadwal Guru</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.assessments*') ? 'active' : '' }}"
                    href="{{ route('admin.assessments.index') }}">
                    <span class="nav-icon">
                        <i class="bi bi-journal-check"></i>
                    </span>
                    <span class="nav-text">Penilaian Guru</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.presences*') ? 'active' : '' }}"
                    href="{{ route('admin.presences') }}">
                    <span class="nav-icon">
                        <i class="bi bi-card-checklist"></i>
                    </span>
                    <span class="nav-text">Rekap Absensi</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.salary*') ? 'active' : '' }}"
                    href="{{ route('admin.salary') }}">
                    <span class="nav-icon">
                        <i class="bi bi-cash-stack"></i>
                    </span>
                    <span class="nav-text">Penggajian</span>
                </a>

                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                    href="{{ route('profile.edit') }}">
                    <span class="nav-icon">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <span class="nav-text">Profil</span>
                </a>

            </nav>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <form class="d-none d-md-flex ms-3 flex-grow-1">
                        <input class="form-control search-input" type="search"
                            placeholder="Cari guru, jadwal, absensi">
                    </form>
                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle>
                            <i class="bi bi-moon-stars" data-theme-icon></i>
                        </button>
                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        Profil
                                    </a>
                                </li>
                                <li hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('vendor/adminhmd/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminhmd/assets/js/main.js') }}"></script>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('adminTheme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = savedTheme ? savedTheme === 'dark' : prefersDark;

            document.body.classList.toggle('theme-dark', isDark);

            const button = document.getElementById('themeToggle');
            const label = button?.querySelector('.theme-label');
            const icon = button?.querySelector('i');

            const updateToggle = () => {
                const dark = document.body.classList.contains('theme-dark');
                if (button) {
                    button.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    if (icon) {
                        icon.className = dark ? 'bi bi-sun-fill me-1' : 'bi bi-moon-stars-fill me-1';
                    }
                    if (label) {
                        label.textContent = dark ? 'Light' : 'Dark';
                    }
                }
            };

            updateToggle();

            button?.addEventListener('click', () => {
                const dark = !document.body.classList.contains('theme-dark');
                document.body.classList.toggle('theme-dark', dark);
                localStorage.setItem('adminTheme', dark ? 'dark' : 'light');
                updateToggle();
            });
        })();
    </script>

</html>
