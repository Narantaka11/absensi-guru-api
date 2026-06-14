<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Jadwal Mengajar Guru</h2>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Guru</th>
                            <th class="px-4 py-3 text-center font-semibold">Hari</th>
                            <th class="px-4 py-3 text-center font-semibold">Jam Mulai</th>
                            <th class="px-4 py-3 text-center font-semibold">Jam Selesai</th>
                            <th class="px-4 py-3 text-left font-semibold">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                            <th class="px-4 py-3 text-center font-semibold">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="font-medium">{{ $schedule->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $schedule->user->email }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">{{ \App\Models\Schedule::DAY_NAMES[$schedule->day_of_week] ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $schedule->start_time }}</td>
                                <td class="px-4 py-3 text-center">{{ $schedule->end_time }}</td>
                                <td class="px-4 py-3">{{ $schedule->subject }}</td>
                                <td class="px-4 py-3">{{ $schedule->class_name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $schedule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('teacher.detail', ['user' => $schedule->user->id]) }}" class="text-blue-600 hover:text-blue-900">
                                        Lihat Guru
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada jadwal mengajar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
