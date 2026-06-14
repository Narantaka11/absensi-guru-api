<x-admin-layout>
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard Absensi Guru
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Monitoring kehadiran dan performa guru
                </p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6">
                <form action="{{ route('admin.dashboard') }}" method="GET"
                    class="flex flex-wrap gap-4 items-end">

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Bulan
                        </label>

                        <select name="month"
                            class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Tahun
                        </label>

                        <select name="year"
                            class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Insight Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

            <!-- Total Guru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Total Guru
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            {{ count($attendanceSummary) }}
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Guru aktif
                        </p>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        👨‍🏫
                    </div>
                </div>
            </div>

            <!-- Rata-rata -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Rata-rata Kehadiran
                        </p>

                        <h2 class="text-3xl font-bold text-blue-600 mt-2">
                            {{ $statistics['average_attendance'] }}%
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Kehadiran bulanan
                        </p>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        📊
                    </div>
                </div>
            </div>

            <!-- Hadir -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Total Hadir
                        </p>

                        <h2 class="text-3xl font-bold text-green-600 mt-2">
                            {{ $statistics['present'] }}
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Kehadiran tercatat
                        </p>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                        ✅
                    </div>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Total Terlambat
                        </p>

                        <h2 class="text-3xl font-bold text-yellow-500 mt-2">
                            {{ $statistics['late'] }}
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Keterlambatan guru
                        </p>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center">
                        ⏰
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Guru -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">
                    Rekap Absensi Guru
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Statistik kehadiran guru bulan ini
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Nama Guru
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Hadir
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Terlambat
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Tidak Hadir
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Sakit
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Izin
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Persentase
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-4 py-4 text-center font-semibold text-gray-600">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendanceSummary as $record)

                            @php
                                $percentage = $record['percentage'];

                                if($percentage >= 90){
                                    $status = 'Sangat Baik';
                                    $badge = 'bg-green-100 text-green-700';
                                } elseif($percentage >= 75){
                                    $status = 'Baik';
                                    $badge = 'bg-blue-100 text-blue-700';
                                } else {
                                    $status = 'Perlu Evaluasi';
                                    $badge = 'bg-red-100 text-red-700';
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-800">
                                            {{ $record['teacher']->name }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $record['teacher']->email }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 font-medium">
                                        {{ $record['present'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-medium">
                                        {{ $record['late'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-medium">
                                        {{ $record['absent'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    {{ $record['sick'] }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    {{ $record['permission'] }}
                                </td>

                                <td class="px-4 py-4 text-center font-semibold text-gray-700">
                                    {{ $record['percentage'] }}%
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-xl text-xs font-semibold {{ $badge }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('teacher.detail', ['user' => $record['teacher']->id, 'month' => $currentMonth, 'year' => $currentYear]) }}"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-6 text-center text-gray-500">
                                    Tidak ada data guru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>
