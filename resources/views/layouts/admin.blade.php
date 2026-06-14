<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100"
      x-data="{ sidebarOpen: true }">

<div class="min-h-screen flex">

    <!-- Sidebar -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform lg:translate-x-0 lg:static lg:inset-0"
    >
        <div class="flex flex-col h-full">

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Guru -->
                <div class="space-y-1">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Guru
                    </div>

                    <a href="{{ route('admin.teachers') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.teachers*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Daftar Guru
                    </a>

                    <a href="{{ route('admin.schedules') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.schedules*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jadwal Guru
                    </a>

                    <a href="{{ route('admin.assessments.index') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.assessments*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Penilaian Guru
                    </a>
                </div>

                <!-- Absensi -->
                <div class="space-y-1">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Absensi
                    </div>

                    <a href="{{ route('admin.presences') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.presences') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Rekap Absensi
                    </a>

                    <a href="{{ route('admin.presences.crud.index') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.presences.crud*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Kelola Absensi
                    </a>
                </div>

                <!-- Penggajian -->
                <div class="space-y-1">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Penggajian
                    </div>

                    <a href="{{ route('admin.salary') }}"
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.salary*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Data Penggajian
                    </a>
                </div>

            </nav>

            <!-- User Menu -->
            <div class="border-t border-gray-200 p-4">

                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>

                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('profile.edit') }}"
                       class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-0">

        <!-- Topbar -->
        <header class="bg-white shadow-sm border-b border-gray-200">

            <div class="px-6 py-4 flex items-center justify-between">

                <div class="flex items-center">

                    <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-4 p-2 rounded-lg text-gray-500 hover:bg-gray-100">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $title ?? 'Admin Dashboard' }}
                    </h1>

                </div>

            </div>

        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
