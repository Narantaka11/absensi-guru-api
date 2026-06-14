<x-admin-layout>
    <x-slot name="title">Edit Jadwal Guru: {{ $user->name }}</x-slot>

    <div class="max-w-3xl mx-auto">
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Jadwal Mengajar</h3>
                    <a href="{{ route('teacher.detail', ['user' => $user->id]) }}" class="text-blue-600 hover:text-blue-900">
                        ← Kembali ke Detail Guru
                    </a>
                </div>
                    <form action="{{ route('admin.teacher.schedules.update', ['user' => $user, 'schedule' => $schedule]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium mb-1">Hari</label>
                            <select name="day_of_week" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                                @foreach($dayNames as $key => $dayName)
                                    <option value="{{ $key }}" {{ old('day_of_week', $schedule->day_of_week) == $key ? 'selected' : '' }}>{{ $dayName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                                <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
                            <input type="text" name="subject" value="{{ old('subject', $schedule->subject) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kelas</label>
                            <input type="text" name="class_name" value="{{ old('class_name', $schedule->class_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="text-sm text-gray-700">Aktifkan jadwal ini</label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('teacher.detail', ['user' => $user->id]) }}" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
