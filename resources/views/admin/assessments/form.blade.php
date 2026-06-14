<x-admin-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $assessment ? 'Edit Penilaian Guru' : 'Tambah Penilaian Guru' }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $assessment ? 'Ubah nilai penilaian untuk guru ini.' : 'Isi formulir untuk menambahkan penilaian guru.' }}
                </p>
            </div>
            <a href="{{ route('admin.assessments.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <form method="POST"
                  action="{{ $assessment ? route('admin.assessments.update', $assessment) : route('admin.assessments.store') }}">
                @csrf
                @if($assessment) @method('PUT') @endif

                <div class="p-6 space-y-5">
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Guru <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" name="user_id"
                                class="w-full rounded-lg border @error('user_id') border-red-500 @else border-gray-300 @enderror bg-white text-gray-900 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('user_id', $assessment?->user_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} @if($teacher->teacher?->nip) ({{ $teacher->teacher->nip }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="month" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Bulan <span class="text-red-500">*</span>
                            </label>
                            <select id="month" name="month"
                                    class="w-full rounded-lg border @error('month') border-red-500 @else border-gray-300 @enderror bg-white text-gray-900 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('month', $assessment?->month ?? now()->month) == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <select id="year" name="year"
                                    class="w-full rounded-lg border @error('year') border-red-500 @else border-gray-300 @enderror bg-white text-gray-900 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                    <option value="{{ $y }}" {{ old('year', $assessment?->year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            @error('year')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach(['absensi' => 'Absensi', 'disiplin' => 'Disiplin', 'keterampilan' => 'Keterampilan', 'produktivitas' => 'Produktivitas'] as $field => $label)
                            <div>
                                <label for="{{ $field }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    {{ $label }} <span class="text-red-500">*</span>
                                </label>
                                <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" max="100" step="0.01"
                                       value="{{ old($field, $assessment?->{$field} ?? 0) }}"
                                       class="w-full rounded-lg border @error($field) border-red-500 @else border-gray-300 @enderror bg-white text-gray-900 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                       oninput="recalculateTotal()">
                                @error($field)
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label for="total" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Total (Rata-rata)
                        </label>
                        <input id="total" name="total" type="text" readonly
                               value="{{ old('total', $assessment?->total ?? 0) }}"
                               class="w-full rounded-lg border border-gray-300 bg-gray-100 text-gray-900 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <a href="{{ route('admin.assessments.index') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                        {{ $assessment ? 'Simpan Perubahan' : 'Simpan Penilaian' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function recalculateTotal() {
            const fields = ['absensi', 'disiplin', 'keterampilan', 'produktivitas'];
            let sum = 0;
            let valid = true;

            fields.forEach(name => {
                const el = document.getElementById(name);
                const value = parseFloat(el.value);
                if (isNaN(value)) {
                    valid = false;
                } else {
                    sum += value;
                }
            });

            const totalField = document.getElementById('total');
            totalField.value = valid ? (sum / 4).toFixed(2) : 0;
        }

        document.addEventListener('DOMContentLoaded', recalculateTotal);
    </script>
</x-admin-layout>
