<x-admin-layout>

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Data Penggajian Guru
                </h2>
                <p class="text-gray-500 text-sm mt-1">
                    Rekap penggajian guru berdasarkan absensi
                </p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.salary') }}" method="GET"
                  class="flex flex-wrap items-end gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bulan
                    </label>

                    <select name="month"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @foreach($months as $num => $name)
                            <option value="{{ $num }}"
                                {{ $num == $currentMonth ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun
                    </label>

                    <select name="year"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}"
                                {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor

                    </select>
                </div>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>

            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Daftar Gaji Guru
                </h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">
                            Nama Guru
                        </th>

                        <th class="px-6 py-4 text-right font-semibold text-gray-700">
                            Gaji Pokok
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-700">
                            Hari Hadir
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-700">
                            Hari Absen
                        </th>

                        <th class="px-6 py-4 text-right font-semibold text-gray-700">
                            Potongan Absensi
                        </th>

                        <th class="px-6 py-4 text-right font-semibold text-gray-700">
                            Potongan Terlambat
                        </th>

                        <th class="px-6 py-4 text-right font-semibold text-gray-700">
                            Gaji Total
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-700">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-700">
                            Aksi
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                    @forelse($salaries as $salary)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $salary->user->name }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700">
                                Rp {{ number_format($salary->base_salary, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-green-100 text-green-700 font-medium">
                                    {{ $salary->total_present_days }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-red-100 text-red-700 font-medium">
                                    {{ $salary->total_absent_days }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-semibold text-red-600">
                                    -Rp {{ number_format($salary->deduction_for_absence, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-semibold text-red-600">
                                    -Rp {{ number_format($salary->deduction_for_late, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($salary->total_salary, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">

                                @if($salary->status == 'draft')
                                    <span id="status-badge-{{ $salary->id }}"
                                          class="px-3 py-1 rounded-lg bg-gray-600 text-white text-xs font-semibold">
                                        Draft
                                    </span>

                                @elseif($salary->status == 'approved')
                                    <span id="status-badge-{{ $salary->id }}"
                                          class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs font-semibold">
                                        Approved
                                    </span>

                                @else
                                    <span id="status-badge-{{ $salary->id }}"
                                          class="px-3 py-1 rounded-lg bg-green-600 text-white text-xs font-semibold">
                                        Paid
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-center">
                                <div id="actions-{{ $salary->id }}" class="flex items-center justify-center gap-2">

                                    {{-- Always show Detail --}}
                                    <button onclick="viewDetail({{ $salary->id }})"
                                            class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                        Detail
                                    </button>

                                    {{-- draft → Approve --}}
                                    @if($salary->status == 'draft')
                                        <button id="approve-btn-{{ $salary->id }}"
                                                onclick="approvePayroll({{ $salary->id }})"
                                                class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Approve
                                        </button>
                                    @endif

                                    {{-- approved → Mark as Paid + Revert --}}
                                    @if($salary->status == 'approved')
                                        <button id="paid-btn-{{ $salary->id }}"
                                                onclick="markAsPaid({{ $salary->id }})"
                                                class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Mark as Paid
                                        </button>
                                        <button id="revert-btn-{{ $salary->id }}"
                                                onclick="revertToDraft({{ $salary->id }})"
                                                class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Revert
                                        </button>
                                    @endif

                                    {{-- paid → readonly (Detail only, sudah di-render di atas) --}}

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data penggajian
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200">
                {{ $salaries->links() }}
            </div>

        </div>

        <!-- Summary -->
        @if($salaries->count() > 0)

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    Ringkasan Penggajian
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Total Gaji Pokok
                        </p>

                        <p class="text-3xl font-bold text-gray-900">
                            Rp {{ number_format($salaries->sum('base_salary'), 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Total Potongan
                        </p>

                        <p class="text-3xl font-bold text-red-600">
                            -Rp {{ number_format($salaries->sum('deduction_for_absence') + $salaries->sum('deduction_for_late'), 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Total Gaji Bersih
                        </p>

                        <p class="text-3xl font-bold text-green-600">
                            Rp {{ number_format($salaries->sum('total_salary'), 0, ',', '.') }}
                        </p>
                    </div>

                </div>

            </div>

        @endif

    </div>

</x-admin-layout>
