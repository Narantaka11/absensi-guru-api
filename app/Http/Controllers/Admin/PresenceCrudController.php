<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PresenceCrudController extends Controller
{
    // Status options yang tersedia
    private const STATUSES = [
        'hadir'       => 'Hadir (On Time)',
        'terlambat'   => 'Terlambat',
        'sakit'       => 'Sakit',
        'izin'        => 'Izin',
        'tidak_hadir' => 'Alpa (Tidak Hadir)',
    ];

    /**
     * Tampilkan daftar semua absensi dengan filter & pencarian.
     */
    public function index(Request $request): View
    {
        $query = Presence::with(['user.teacher'])
            ->orderByDesc('presence_date')
            ->orderBy('check_in_time');

        // Filter: guru
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: bulan & tahun
        if ($request->filled('month')) {
            $query->whereMonth('presence_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('presence_date', $request->year);
        }

        // Filter: tanggal spesifik
        if ($request->filled('date')) {
            $query->whereDate('presence_date', $request->date);
        }

        $presences = $query
            ->paginate(20)
            ->withQueryString();

        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        // Statistik
        $statsQuery = Presence::query();

        if ($request->filled('user_id')) {
            $statsQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('month')) {
            $statsQuery->whereMonth('presence_date', $request->month);
        }

        if ($request->filled('year')) {
            $statsQuery->whereYear('presence_date', $request->year);
        }

        if ($request->filled('date')) {
            $statsQuery->whereDate('presence_date', $request->date);
        }

        $stats = [
            'hadir'       => (clone $statsQuery)->where('status', 'hadir')->count(),
            'terlambat'   => (clone $statsQuery)->where('status', 'terlambat')->count(),
            'sakit'       => (clone $statsQuery)->where('status', 'sakit')->count(),
            'izin'        => (clone $statsQuery)->where('status', 'izin')->count(),
            'tidak_hadir' => (clone $statsQuery)->where('status', 'tidak_hadir')->count(),
            'total'       => (clone $statsQuery)->count(),
        ];

        return view('admin.presences.crud.index', [
            'presences' => $presences,
            'teachers'  => $teachers,
            'statuses'  => self::STATUSES,
            'stats'     => $stats,
            'filters'   => $request->only([
                'user_id',
                'status',
                'month',
                'year',
                'date'
            ]),
            'title'     => 'Kelola Data Absensi',
        ]);
    }

    /**
     * Form tambah absensi manual admin.
     */
    public function create(): View
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('admin.presences.crud.create', [
            'teachers' => $teachers,
            'statuses' => self::STATUSES,
            'title'    => 'Tambah Absensi',
        ]);
    }

    /**
     * Simpan absensi manual admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'        => ['required', 'exists:users,id'],
            'presence_date'  => ['required', 'date'],
            'status'         => ['required', 'in:' . implode(',', array_keys(self::STATUSES))],

            'check_in_time'  => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],

            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        Presence::create($validated);

        return redirect()
            ->route('admin.presences.crud.index')
            ->with('success', 'Absensi berhasil ditambahkan.');
    }

    /**
     * Detail absensi.
     */
    public function show(Presence $presence): View
    {
        $presence->load(['user.teacher', 'location']);

        return view('admin.presences.crud.show', [
            'presence' => $presence,
            'title'    => 'Detail Absensi',
        ]);
    }

    /**
     * Form edit absensi.
     */
    public function edit(Presence $presence): View
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('admin.presences.crud.edit', [
            'presence' => $presence,
            'teachers' => $teachers,
            'statuses' => self::STATUSES,
            'title'    => 'Edit Absensi',
        ]);
    }

    /**
     * Update absensi.
     */
    public function update(Request $request, Presence $presence): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'        => ['required', 'exists:users,id'],
            'presence_date'  => ['required', 'date'],
            'status'         => ['required', 'in:' . implode(',', array_keys(self::STATUSES))],

            'check_in_time'  => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],

            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $presence->update($validated);

        return redirect()
            ->route('admin.presences.crud.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Hapus absensi.
     */
    public function destroy(Presence $presence): RedirectResponse
    {
        try {

            // Hapus foto check in
            if ($presence->check_in_photo) {
                Storage::disk('public')->delete($presence->check_in_photo);
            }

            // Hapus foto check out
            if ($presence->check_out_photo) {
                Storage::disk('public')->delete($presence->check_out_photo);
            }

            // Hapus data
            $presence->delete();

            return redirect()
                ->route('admin.presences.crud.index')
                ->with('success', 'Data absensi berhasil dihapus.');

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Gagal menghapus absensi: ' . $e->getMessage()
            );
        }
    }
}
