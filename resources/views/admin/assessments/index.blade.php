<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Penilaian Kinerja Guru</h2>
                    <p class="text-sm text-gray-500 mt-1">Tambah, ubah, dan hapus penilaian guru untuk periode tertentu.</p>
                </div>
                <a href="{{ route('admin.assessments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tambah Penilaian
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="month" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="year" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filter</button>
                        <a href="{{ route('admin.assessments.index') }}" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Reset</a>
                    </div>
                </form>
            </div>

            @if($unassessedTeachers->count() > 0)
                <div class="mb-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Guru yang Belum Dinilai</h3>
                            <p class="text-sm text-yellow-700">Terdapat {{ $unassessedTeachers->count() }} guru belum memiliki penilaian untuk periode ini.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($unassessedTeachers as $teacher)
                            <div class="rounded-xl border border-yellow-300 bg-white p-4">
                                <div class="font-semibold text-gray-900">{{ $teacher->name }}</div>
                                <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                                <div class="text-sm text-gray-500">NIP: {{ $teacher->teacher?->nip ?? '-' }}</div>
                                <div class="text-sm text-gray-500">Mapel: {{ $teacher->teacher?->subject ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Guru</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Periode</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Absensi</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Disiplin</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Keterampilan</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Produktivitas</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Total</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($assessments as $assessment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $assessment->user->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ date('F Y', mktime(0,0,0,$assessment->month,1,$assessment->year)) }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ number_format($assessment->absensi, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ number_format($assessment->disiplin, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ number_format($assessment->keterampilan, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ number_format($assessment->produktivitas, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ number_format($assessment->total, 2) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form method="POST" action="{{ route('admin.assessments.destroy', $assessment) }}" onsubmit="return confirm('Hapus penilaian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada penilaian untuk periode {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $assessments->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
