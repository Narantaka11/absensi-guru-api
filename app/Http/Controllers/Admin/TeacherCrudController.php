<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherCrudController extends Controller
{
    public function create()
    {
        $locations = Location::orderBy('name')->get();

        return view('admin.teachers.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],

            'nip' => ['required', 'unique:teachers,nip'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'subject' => ['nullable'],
            'base_salary' => ['required', 'numeric'],
            'location_id' => ['nullable', 'exists:locations,id'],
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_TEACHER,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'base_salary' => $validated['base_salary'],
                'location_id' => $validated['location_id'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.teachers')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');

        $locations = Location::orderBy('name')->get();

        return view('admin.teachers.edit', compact(
            'teacher',
            'locations'
        ));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $teacher->load('user');

        $validated = $request->validate([
            'name' => ['required'],
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $teacher->user_id
            ],

            'nip' => [
                'required',
                'unique:teachers,nip,' . $teacher->id
            ],

            'phone' => ['nullable'],
            'address' => ['nullable'],
            'subject' => ['nullable'],
            'base_salary' => ['required', 'numeric'],
            'location_id' => ['nullable', 'exists:locations,id'],
        ]);

        DB::transaction(function () use ($teacher, $validated) {

            $teacher->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $teacher->update([
                'nip' => $validated['nip'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'base_salary' => $validated['base_salary'],
                'location_id' => $validated['location_id'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.teachers')
            ->with('success', 'Guru berhasil diperbarui');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {

            $user = $teacher->user;

            $teacher->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.teachers')
            ->with('success', 'Guru berhasil dihapus');
    }
}
