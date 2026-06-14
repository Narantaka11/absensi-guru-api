<?php

namespace App\Console\Commands;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateAlphaAttendance extends Command
{
    protected $signature = 'attendance:generate-alpha';

    protected $description =
        'Generate alpa attendance automatically';

    public function handle(): void
    {
        $today = now()->toDateString();
        // 🔥 AMBIL SEMUA GURU
        $teachers = User::where(
            'role',
            'teacher'
        )->get();
        foreach ($teachers as $teacher) {
            // 🔥 CEK APAKAH SUDAH ABSEN
            $alreadyExists = Presence::where(
                'user_id',
                $teacher->id
            )
            ->whereDate(
                'presence_date',
                $today
            )
            ->exists();
            // 🔥 JIKA BELUM ADA ABSENSI
            if (!$alreadyExists) {
                Presence::create([
                    'user_id' => $teacher->id,
                    'presence_date' => $today,
                    'status' => 'alpa',
                ]);
                $this->info(
                    "Alpa generated for {$teacher->name}"
                );
            }
        }
        $this->info(
            'Generate alpa selesai.'
        );
    }
}
