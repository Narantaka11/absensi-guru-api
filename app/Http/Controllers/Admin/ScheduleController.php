<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'subject' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->schedules()->create([
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('teacher.detail', ['user' => $user->id])
            ->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    public function edit(User $user, Schedule $schedule)
    {
        abort_if($schedule->user_id !== $user->id, 404);

        return view('admin.schedules.edit', [
            'user' => $user,
            'schedule' => $schedule,
            'dayNames' => Schedule::DAY_NAMES,
        ]);
    }

    public function update(Request $request, User $user, Schedule $schedule)
    {
        abort_if($schedule->user_id !== $user->id, 404);

        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'subject' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $schedule->update([
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('teacher.detail', ['user' => $user->id])
            ->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    public function destroy(User $user, Schedule $schedule)
    {
        abort_if($schedule->user_id !== $user->id, 404);

        $schedule->delete();

        return redirect()->route('teacher.detail', ['user' => $user->id])
            ->with('success', 'Jadwal mengajar berhasil dihapus.');
    }
}
