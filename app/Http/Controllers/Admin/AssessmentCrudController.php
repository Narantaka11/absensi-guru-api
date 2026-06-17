<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssessmentCrudController extends Controller
{
    public function index(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $query = Assessment::with(['user.teacher'])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('saw_score');
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $assessments = $query->paginate(20)->withQueryString();

        $assessedUserIds = Assessment::when($request->filled('month'), fn($q) => $q->where('month', $request->month))
            ->when($request->filled('year'), fn($q) => $q->where('year', $request->year))
            ->pluck('user_id');

        $unassessedTeachers = User::where('role', User::ROLE_TEACHER)
            ->whereNotIn('id', $assessedUserIds)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('admin.assessments.index', [
            'assessments' => $assessments,
            'unassessedTeachers' => $unassessedTeachers,
            'month' => $month,
            'year' => $year,
            'title' => 'Penilaian Kinerja Guru',
        ]);
    }

    public function create(): View
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('admin.assessments.form', [
            'assessment' => null,
            'teachers' => $teachers,
            'title' => 'Tambah Penilaian Guru',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'absensi' => ['required', 'numeric', 'min:0', 'max:100'],
            'disiplin' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterampilan' => ['required', 'numeric', 'min:0', 'max:100'],
            'produktivitas' => ['required', 'numeric', 'min:0', 'max:100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $user = User::find($validated['user_id']);
        if (!$user || !$user->isTeacher()) {
            return back()->withInput()->withErrors(['user_id' => 'User ini bukan guru.']);
        }

        $exists = Assessment::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['month' => 'Penilaian untuk guru ini pada periode tersebut sudah ada.']);
        }

        $validated['total'] = (
            $validated['absensi'] +
            $validated['disiplin'] +
            $validated['keterampilan'] +
            $validated['produktivitas']
        ) / 4;

        DB::transaction(function () use ($validated) {
            Assessment::create($validated);
            Assessment::calculateSaw(
                $validated['month'],
                $validated['year']
            );
        });

        return redirect()
            ->route('admin.assessments.index')
            ->with('success', 'Penilaian guru berhasil ditambahkan.');
    }

    public function edit(Assessment $assessment): View
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('admin.assessments.form', [
            'assessment' => $assessment,
            'teachers' => $teachers,
            'title' => 'Edit Penilaian Guru',
        ]);
    }

    public function update(Request $request, Assessment $assessment): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'absensi' => ['required', 'numeric', 'min:0', 'max:100'],
            'disiplin' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterampilan' => ['required', 'numeric', 'min:0', 'max:100'],
            'produktivitas' => ['required', 'numeric', 'min:0', 'max:100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $user = User::find($validated['user_id']);
        if (!$user || !$user->isTeacher()) {
            return back()->withInput()->withErrors(['user_id' => 'User ini bukan guru.']);
        }

        $exists = Assessment::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $assessment->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['month' => 'Penilaian untuk guru ini pada periode tersebut sudah ada.']);
        }

        $validated['total'] = (
            $validated['absensi'] +
            $validated['disiplin'] +
            $validated['keterampilan'] +
            $validated['produktivitas']
        ) / 4;

        DB::transaction(function () use ($assessment, $validated) {
            $assessment->update($validated);
            Assessment::calculateSaw(
                $validated['month'],
                $validated['year']
            );
        });

        return redirect()
            ->route('admin.assessments.index')
            ->with('success', 'Penilaian guru berhasil diperbarui.');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $month = $assessment->month;
        $year = $assessment->year;
        $assessment->delete();
        Assessment::calculateSaw($month, $year);
        return back()->with(
            'success',
            'Penilaian guru berhasil dihapus.'
        );
    }
}
