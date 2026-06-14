<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * CRUD untuk data guru.
 * Semua route di sini sudah diproteksi middleware auth:sanctum + api.admin.
 */
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $paginator = User::where('role', User::ROLE_TEACHER)
            ->with('teacher.location')
            ->paginate($validated['per_page'] ?? 20);

        return $this->success('Daftar guru berhasil diambil.', [
            'items'      => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8'],
            'nip'          => ['required', 'string', 'unique:teachers,nip'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
            'subject'      => ['nullable', 'string', 'max:255'],
            'base_salary'  => ['required', 'numeric', 'min:0'],
            'location_id'  => ['nullable', 'integer', 'exists:locations,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => User::ROLE_TEACHER,
            ]);

            Teacher::create([
                'user_id'     => $user->id,
                'nip'         => $validated['nip'],
                'phone'       => $validated['phone'],
                'address'     => $validated['address'],
                'subject'     => $validated['subject'],
                'base_salary' => $validated['base_salary'],
                'location_id' => $validated['location_id'],
            ]);
        });

        return $this->success('Guru berhasil dibuat.', [], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        if (!$user->isTeacher()) {
            return $this->error('User ini bukan guru.', null, 404);
        }

        $user->load('teacher.location');

        return $this->success('Detail guru berhasil diambil.', [
            'teacher' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        if (!$user->isTeacher()) {
            return $this->error('User ini bukan guru.', null, 404);
        }

        $validated = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'email'        => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'nip'          => ['sometimes', 'string', 'unique:teachers,nip,' . $user->teacher->id],
            'phone'        => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
            'subject'      => ['nullable', 'string', 'max:255'],
            'base_salary'  => ['sometimes', 'numeric', 'min:0'],
            'location_id'  => ['nullable', 'integer', 'exists:locations,id'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            if (isset($validated['name']) || isset($validated['email'])) {
                $user->update(array_intersect_key($validated, array_flip(['name', 'email'])));
            }

            $user->teacher->update(array_diff_key($validated, array_flip(['name', 'email'])));
        });

        $user->load('teacher.location');

        return $this->success('Guru berhasil diperbarui.', [
            'teacher' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        if (!$user->isTeacher()) {
            return $this->error('User ini bukan guru.', null, 404);
        }

        DB::transaction(function () use ($user) {
            $user->teacher->delete();
            $user->delete();
        });

        return $this->success('Guru berhasil dihapus.');
    }

    private function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => null,
        ], $status);
    }

    private function error(string $message, mixed $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => [],
            'errors'  => $errors,
        ], $status);
    }
}
