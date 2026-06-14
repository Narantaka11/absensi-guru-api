<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $schedules = Schedule::query()

            ->where(
                'user_id',
                $user->id
            )

            ->where(
                'is_active',
                true
            )

            ->orderBy('day_of_week')

            ->orderBy('start_time')

            ->get();

        return response()->json([

            'success' => true,

            'message' =>
                'Data jadwal berhasil diambil.',

            'data' => [

                'schedules' => $schedules
            ],

            'errors' => null
        ]);
    }
}
