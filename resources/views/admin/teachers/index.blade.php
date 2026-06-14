<x-admin-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Daftar Guru</h2>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">NIP</th>
                            <th class="px-4 py-3 text-left font-semibold">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $teacher->name }}</td>
                                <td class="px-4 py-3">{{ $teacher->email }}</td>
                                <td class="px-4 py-3">{{ $teacher->teacher?->nip ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $teacher->teacher?->subject ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $teacher->teacher?->location?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('teacher.detail', ['user' => $teacher->id]) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada data guru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
