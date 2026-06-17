<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }
    /**
     * Daftar Guru
     */
    public function teachers(Request $request): View
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher.location')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(20)
            ->withQueryString();
        return view('admin.teachers.index', [
            'teachers' => $teachers,
            'title' => 'Daftar Guru'
        ]);
    }
    /**
     * Jadwal Guru
     */
    public function schedules(Request $request): View
    {
        $schedules = Schedule::with(['user.teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.schedules.index', [
            'schedules' => $schedules,
            'title' => 'Jadwal Mengajar Guru'
        ]);
    }

    /**
     * Rekap Absensi
     */
    public function presences(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $summary = $this->attendanceService->getAllTeachersSummary($month, $year);

        return view('admin.presences.index', [
            'summary' => $summary,
            'month' => $month,
            'year' => $year,
            'title' => 'Rekap Absensi Guru'
        ]);
    }

    /**
     * Export Rekap Absensi ke CSV
     */
    public function exportPresences(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $summary = $this->attendanceService->getAllTeachersSummary($month, $year);

        $filename = "rekap-absensi-{$month}-{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($summary) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama Guru',
                'Email',
                'NIP',
                'Mata Pelajaran',
                'Hadir',
                'Terlambat',
                'Tidak Hadir',
                'Sakit',
                'Izin',
                'Total Hari',
                'Persentase (%)',
                'Total Jam Kerja'
            ]);

            // Data
            foreach ($summary as $row) {
                fputcsv($file, [
                    $row['teacher']['name'],
                    $row['teacher']['email'],
                    $row['teacher']['nip'] ?? '-',
                    $row['teacher']['subject'] ?? '-',
                    $row['summary']['hadir'],
                    $row['summary']['terlambat'],
                    $row['summary']['tidak_hadir'],
                    $row['summary']['sakit'],
                    $row['summary']['izin'],
                    $row['summary']['total'],
                    number_format($row['percentage'], 2),
                    number_format($row['work_hours'], 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Penilaian Kinerja Guru
     */
    public function assessments(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $assessments = Assessment::with(['user.teacher'])
            ->where('month', $month)
            ->where('year', $year)
            ->paginate(20);

        // Get teachers without assessment for this period
        $assessedUserIds = Assessment::where('month', $month)
            ->where('year', $year)
            ->pluck('user_id');

        $unassessedTeachers = User::where('role', User::ROLE_TEACHER)
            ->whereNotIn('id', $assessedUserIds)
            ->with('teacher')
            ->get();

        return view('admin.assessments.index', [
            'assessments' => $assessments,
            'unassessedTeachers' => $unassessedTeachers,
            'month' => $month,
            'year' => $year,
            'title' => 'Penilaian Kinerja Guru'
        ]);
    }
}
