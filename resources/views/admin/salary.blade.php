<x-admin-layout>

    <div class="page-heading mb-4">

        <div class="page-heading-copy">

            <span class="page-icon">
                <i class="bi bi-cash-stack"></i>
            </span>

            <div>
                <p class="eyebrow mb-1">
                    Penggajian
                </p>

                <h1 class="h3 mb-1">
                    Data Penggajian Guru
                </h1>

                <p class="text-muted mb-0">
                    Rekap penggajian guru berdasarkan absensi
                </p>
            </div>

        </div>

    </div>

    <section class="panel mb-4">

        <div class="panel-header">
            <h2 class="h5 mb-0">
                Filter Periode
            </h2>
        </div>

        <div class="p-4">

            <form action="{{ route('admin.salary') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        Bulan
                    </label>

                    <select name="month" class="form-select">

                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>

                                {{ $name }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        Tahun
                    </label>

                    <select name="year" class="form-select">

                        @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>

                                {{ $y }}

                            </option>
                        @endfor

                    </select>

                </div>

                <div class="col-md-4">

                    <button type="submit" class="btn btn-primary">

                        Filter

                    </button>

                </div>

            </form>

        </div>

    </section>

    <section class="panel">

        <div class="panel-header">

            <div>

                <h2 class="h5 mb-1">
                    Daftar Gaji Guru
                </h2>

                <p class="text-muted mb-0">
                    Data penggajian berdasarkan periode yang dipilih.
                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Nama Guru</th>

                        <th class="text-end">
                            Gaji Pokok
                        </th>

                        <th class="text-center">
                            Hadir
                        </th>

                        <th class="text-center">
                            Absen
                        </th>

                        <th class="text-end">
                            Potongan Absensi
                        </th>

                        <th class="text-end">
                            Potongan Terlambat
                        </th>

                        <th class="text-end">
                            Total Gaji
                        </th>

                        <th class="text-center">
                            Status
                        </th>

                        <th class="text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($salaries as $salary)
                        <tr>

                            <td>

                                <div class="fw-semibold">
                                    {{ $salary->user->name }}
                                </div>

                            </td>

                            <td class="text-end">

                                Rp {{ number_format($salary->base_salary, 0, ',', '.') }}

                            </td>

                            <td class="text-center">

                                <span class="badge text-bg-success">

                                    {{ $salary->total_present_days }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge text-bg-danger">

                                    {{ $salary->total_absent_days }}

                                </span>

                            </td>

                            <td class="text-end text-danger fw-semibold">

                                -Rp {{ number_format($salary->deduction_for_absence, 0, ',', '.') }}

                            </td>

                            <td class="text-end text-danger fw-semibold">

                                -Rp {{ number_format($salary->deduction_for_late, 0, ',', '.') }}

                            </td>

                            <td class="text-end">

                                <span class="fw-bold text-success">

                                    Rp {{ number_format($salary->total_salary, 0, ',', '.') }}

                                </span>

                            </td>

                            <td class="text-center">

                                @if ($salary->status == 'draft')
                                    <span id="status-badge-{{ $salary->id }}" class="badge text-bg-secondary">
                                        Draft
                                    </span>
                                @elseif($salary->status == 'approved')
                                    <span id="status-badge-{{ $salary->id }}" class="badge text-bg-primary">
                                        Approved
                                    </span>
                                @else
                                    <span id="status-badge-{{ $salary->id }}" class="badge text-bg-success">
                                        Paid
                                    </span>
                                @endif

                            </td>

                            <td class="text-center">

                                <div id="actions-{{ $salary->id }}"
                                    class="d-flex justify-content-center gap-2 flex-wrap">

                                    <button onclick="viewDetail({{ $salary->id }})"
                                        class="btn btn-sm btn-outline-secondary">

                                        Detail

                                    </button>

                                    @if ($salary->status == 'draft')
                                        <button id="approve-btn-{{ $salary->id }}"
                                            onclick="approvePayroll({{ $salary->id }})"
                                            class="btn btn-sm btn-primary">

                                            Approve

                                        </button>
                                    @endif

                                    @if ($salary->status == 'approved')
                                        <button id="paid-btn-{{ $salary->id }}"
                                            onclick="markAsPaid({{ $salary->id }})" class="btn btn-sm btn-success">

                                            Mark Paid

                                        </button>

                                        <button id="revert-btn-{{ $salary->id }}"
                                            onclick="revertToDraft({{ $salary->id }})"
                                            class="btn btn-sm btn-warning">

                                            Revert

                                        </button>
                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-4 text-muted">

                                Tidak ada data penggajian

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-3 border-top">

            {{ $salaries->links() }}

        </div>

    </section>

    @if ($salaries->count() > 0)
        <div class="row g-4 mt-1">

            <div class="col-md-4">

                <div class="metric-card metric-primary">

                    <div class="metric-copy">

                        <span class="metric-label">
                            Total Gaji Pokok
                        </span>

                        <strong class="metric-value">

                            Rp {{ number_format($salaries->sum('base_salary'), 0, ',', '.') }}

                        </strong>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="metric-card metric-danger">

                    <div class="metric-copy">

                        <span class="metric-label">
                            Total Potongan
                        </span>

                        <strong class="metric-value">

                            Rp
                            {{ number_format($salaries->sum('deduction_for_absence') + $salaries->sum('deduction_for_late'), 0, ',', '.') }}

                        </strong>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="metric-card metric-success">

                    <div class="metric-copy">

                        <span class="metric-label">
                            Total Gaji Bersih
                        </span>

                        <strong class="metric-value">

                            Rp {{ number_format($salaries->sum('total_salary'), 0, ',', '.') }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>
    @endif

</x-admin-layout>
