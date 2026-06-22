<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * CRUD untuk penilaian kinerja guru.
 * Semua route di sini sudah diproteksi middleware auth:sanctum + api.admin.
 */
class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Riwayat penilaian guru login.
     */
    public function myAssessments(Request $request): JsonResponse
    {
        $assessments = Assessment::where(
            'user_id',
            $request->user()->id
        )
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $items = $assessments->map(function ($assessment) {

            $ranking = Assessment::where(
                'month',
                $assessment->month
            )
                ->where(
                    'year',
                    $assessment->year
                )
                ->where(
                    'saw_score',
                    '>',
                    $assessment->saw_score
                )
                ->count() + 1;

            return [
                'id' => $assessment->id,
                'month' => $assessment->month,
                'year' => $assessment->year,

                'absensi' => $assessment->absensi,
                'disiplin' => $assessment->disiplin,
                'keterampilan' => $assessment->keterampilan,
                'produktivitas' => $assessment->produktivitas,

                'total' => $assessment->total,
                'saw_score' => $assessment->saw_score,

                'ranking' => $ranking,
            ];
        });

        return $this->success(
            'Data penilaian berhasil diambil.',
            [
                'items' => $items,
            ]
        );
    }
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2099'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $query = Assessment::with(['user.teacher']);

        if (!empty($validated['teacher_id'])) {
            $query->where('user_id', $validated['teacher_id']);
        }

        if (!empty($validated['month'])) {
            $query->where('month', $validated['month']);
        }

        if (!empty($validated['year'])) {
            $query->where('year', $validated['year']);
        }

        $paginator = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate($validated['per_page'] ?? 20);

        return $this->success('Daftar penilaian berhasil diambil.', [
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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

        // Check if user is teacher
        $user = User::find($validated['user_id']);
        if (!$user->isTeacher()) {
            return $this->error('User ini bukan guru.', null, 422);
        }

        // Check if assessment already exists for this period
        $exists = Assessment::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return $this->error('Penilaian untuk periode ini sudah ada.', null, 422);
        }

        // Calculate total
        $validated['total'] = ($validated['absensi'] + $validated['disiplin'] + $validated['keterampilan'] + $validated['produktivitas']) / 4;

        $assessment = Assessment::create($validated);

        return $this->success('Penilaian berhasil dibuat.', [
            'assessment' => $assessment->load('user.teacher'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assessment $assessment): JsonResponse
    {
        return $this->success('Detail penilaian berhasil diambil.', [
            'assessment' => $assessment->load('user.teacher'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment): JsonResponse
    {
        $validated = $request->validate([
            'absensi' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'disiplin' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'keterampilan' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'produktivitas' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ]);

        $assessment->update($validated);

        // Recalculate total
        $assessment->total = ($assessment->absensi + $assessment->disiplin + $assessment->keterampilan + $assessment->produktivitas) / 4;
        $assessment->save();

        return $this->success('Penilaian berhasil diperbarui.', [
            'assessment' => $assessment->load('user.teacher'),
        ]);
    }

    /**
     * Get teachers who don't have assessment for a specific period.
     */
    public function unassessedTeachers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2099'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $assessedUserIds = Assessment::where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->pluck('user_id');

        $query = User::where('role', User::ROLE_TEACHER)
            ->whereNotIn('id', $assessedUserIds)
            ->with('teacher.location');

        $paginator = $query->paginate($validated['per_page'] ?? 20);

        return $this->success('Daftar guru yang belum dinilai berhasil diambil.', [
            'month' => $validated['month'],
            'year' => $validated['year'],
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    private function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $status);
    }

    private function error(string $message, mixed $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'errors' => $errors,
        ], $status);
    }
}
