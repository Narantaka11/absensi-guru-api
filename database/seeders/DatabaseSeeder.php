<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Presence;
use App\Models\Salary;
use App\Models\Teacher;   // 👈 TAMBAH INI
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        User::create([
            'name'     => 'Admin Sekolah',
            'email'    => 'admin@sekolah.com',
            'password' => bcrypt('password'),
            'role'     => User::ROLE_ADMIN,
        ]);

        $teachers = [
            ['name' => 'Budi Santoso',  'email' => 'budi@sekolah.com'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@sekolah.com'],
            ['name' => 'Ahmad Wijaya',   'email' => 'ahmad@sekolah.com'],
            ['name' => 'Rini Pratiwi',   'email' => 'rini@sekolah.com'],
            ['name' => 'Eka Putra',      'email' => 'eka@sekolah.com'],
        ];

        foreach ($teachers as $teacherData) {
            // 1. Create user
            $teacher = User::create([
                'name'     => $teacherData['name'],
                'email'    => $teacherData['email'],
                'password' => bcrypt('password'),
                'role'     => User::ROLE_TEACHER,
            ]);

            // 👇 TAMBAH INI — profile guru dengan gaji pokok random
            Teacher::create([
                'user_id'    => $teacher->id,
                'nip'        => '19850' . rand(1000, 9999) . '2020' . rand(10, 99),
                'phone'      => '08' . rand(1000000000, 9999999999),
                'address'    => 'Jl. Pendidikan No. ' . rand(1, 100) . ', Jakarta',
                'subject'    => collect(['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS'])->random(),
                'base_salary' => 3_000_000,
            ]);

            // 2. Create presence records
            $this->createPresences($teacher);

            // 3. Create salary record
            $this->createSalary($teacher);
        }
    }

    private function createPresences(User $teacher): void
    {
        $now   = now();
        $month = $now->month;
        $year  = $now->year;

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);

            if ($date->isWeekend()) {
                continue;
            }

            $rand = rand(1, 100);

            if ($rand > 90) {
                // 10% absent
                $status       = 'tidak_hadir';
                $checkInTime  = null;
                $checkOutTime = null;
                $lateMinutes  = 0;
            } elseif ($rand > 75) {
                // 15% sick
                $status       = 'sakit';
                $checkInTime  = null;
                $checkOutTime = null;
                $lateMinutes  = 0;
            } elseif ($rand > 15) {
                // 60% present on time
                $status       = 'hadir';
                $hour         = rand(6, 7);
                $minute       = rand(0, 59);
                $checkInTime  = Carbon::create(null, null, null, $hour, $minute, 0);
                $checkOutTime = Carbon::create(null, null, null, 16 + rand(0, 1), rand(0, 59), 0);
                $lateMinutes  = 0;
            } else {
                // 25% late
                $status       = 'terlambat';
                $hour         = rand(7, 9);
                $minute       = rand(1, 59);
                $checkInTime  = Carbon::create(null, null, null, $hour, $minute, 0);
                $checkOutTime = Carbon::create(null, null, null, 16 + rand(0, 1), rand(0, 59), 0);

                // Calculate late minutes (GUARANTEED POSITIVE)
                $expectedTime = Carbon::create(null, null, null, 7, 0, 0);
                $lateMinutes  = (int) abs($expectedTime->diffInMinutes($checkInTime, false));
            }

            Presence::create([
                'user_id'              => $teacher->id,
                'presence_date'        => $date,
                'check_in_time'        => $checkInTime,
                'check_out_time'       => $checkOutTime,
                'check_in_latitude'    => -6.2088 + (rand(-100, 100) / 10000),
                'check_in_longitude'   => 106.8456 + (rand(-100, 100) / 10000),
                'check_out_latitude'   => -6.2088 + (rand(-100, 100) / 10000),
                'check_out_longitude'  => 106.8456 + (rand(-100, 100) / 10000),
                'status'               => $status,
                'late_minutes'         => $lateMinutes,
                'notes'                => null,
            ]);
        }
    }

    private function createSalary(User $teacher): void
    {
        app(\App\Services\PayrollService::class)
            ->calculate(
                $teacher,
                now()->month,
                now()->year
            );
    }
}
