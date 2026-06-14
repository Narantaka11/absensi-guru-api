<x-admin-layout>
    <x-slot name="title">Detail Guru: {{ $user->name }}</x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-green-600 text-sm font-medium">Hadir</div>
                        <div class="text-3xl font-bold text-green-600 mt-2">{{ $summary['present'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-yellow-600 text-sm font-medium">Terlambat</div>
                        <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $summary['late'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-red-600 text-sm font-medium">Tidak Hadir</div>
                        <div class="text-3xl font-bold text-red-600 mt-2">{{ $summary['absent'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-blue-600 text-sm font-medium">Sakit</div>
                        <div class="text-3xl font-bold text-blue-600 mt-2">{{ $summary['sick'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-600 text-sm font-medium">Izin</div>
                        <div class="text-3xl font-bold text-gray-600 mt-2">{{ $summary['permission'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Salary Info if exists -->
            @if($salary)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Data Penggajian</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 text-sm">Gaji Pokok</p>
                                <p class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($salary->base_salary, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Gaji Total</p>
                                <p class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($salary->total_salary, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Potongan Absensi</p>
                                <p class="text-xl font-bold text-red-600">
                                    Rp {{ number_format($salary->deduction_for_absence, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Potongan Keterlambatan</p>
                                <p class="text-xl font-bold text-red-600">
                                    Rp {{ number_format($salary->deduction_for_late, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-gray-100 rounded">
                            <p class="text-sm">Status: <span class="font-semibold">{{ ucfirst($salary->status) }}</span></p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Teaching Schedule -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Jadwal Mengajar</h3>
                        <span class="text-sm text-gray-500">Kelola jadwal untuk guru ini</span>
                    </div>

                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Hari</th>
                                    <th class="px-4 py-3 text-center font-semibold">Jam Mulai</th>
                                    <th class="px-4 py-3 text-center font-semibold">Jam Selesai</th>
                                    <th class="px-4 py-3 text-left font-semibold">Mata Pelajaran</th>
                                    <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                                    <th class="px-4 py-3 text-center font-semibold">Aktif</th>
                                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($schedules as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ \App\Models\Schedule::DAY_NAMES[$schedule->day_of_week] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">{{ $schedule->start_time }}</td>
                                        <td class="px-4 py-3 text-center">{{ $schedule->end_time }}</td>
                                        <td class="px-4 py-3">{{ $schedule->subject }}</td>
                                        <td class="px-4 py-3">{{ $schedule->class_name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $schedule->is_active ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('admin.teacher.schedules.edit', ['user' => $user, 'schedule' => $schedule]) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <form action="{{ route('admin.teacher.schedules.destroy', ['user' => $user, 'schedule' => $schedule]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus jadwal ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                            Belum ada jadwal.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-base font-semibold text-gray-900 mb-4">Tambah Jadwal Baru</h4>
                        <form action="{{ route('admin.teacher.schedules.store', ['user' => $user]) }}" method="POST" class="grid gap-4 md:grid-cols-2">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium mb-1">Hari</label>
                                <select name="day_of_week" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900">
                                    @foreach(\App\Models\Schedule::DAY_NAMES as $key => $dayName)
                                        <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>{{ $dayName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                                <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" placeholder="Contoh: Matematika" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kelas</label>
                                <input type="text" name="class_name" value="{{ old('class_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" placeholder="Contoh: 10 IPA 1" required>
                            </div>
                            <div class="flex items-center gap-2 md:col-span-2">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="text-sm text-gray-700">Aktifkan jadwal ini</label>
                            </div>
                            <div class="md:col-span-2 text-right">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Jadwal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Riwayat Absensi Bulan ini</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 text-center font-semibold">Jam Masuk</th>
                                    <th class="px-4 py-3 text-center font-semibold">Jam Keluar</th>
                                    <th class="px-4 py-3 text-center font-semibold">Terlambat (Menit)</th>
                                    <th class="px-4 py-3 text-center font-semibold">Status</th>
                                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($presences as $presence)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $presence->presence_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $presence->check_in_time ? $presence->check_in_time->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $presence->check_out_time ? $presence->check_out_time->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($presence->late_minutes > 0)
                                                <span class="text-red-600 font-semibold">{{ $presence->late_minutes }}</span>
                                            @else
                                                <span class="text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-block px-3 py-1 rounded text-white text-xs font-semibold
                                                @if($presence->status == 'hadir') bg-green-600
                                                @elseif($presence->status == 'terlambat') bg-yellow-600
                                                @elseif($presence->status == 'tidak_hadir') bg-red-600
                                                @elseif($presence->status == 'sakit') bg-blue-600
                                                @else bg-gray-600
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $presence->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('presence.detail', $presence) }}" class="text-blue-600 hover:text-blue-900">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                            Tidak ada data absensi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $presences->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
