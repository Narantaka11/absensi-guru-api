<?php

use App\Http\Controllers\API\AdminPresenceController;
use App\Http\Controllers\API\AdminRecapController;
use App\Http\Controllers\API\AssessmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PresenceController;
use App\Http\Controllers\API\PayrollController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\ScheduleController;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {

    // =========================================================================
    // Auth — publik (tidak perlu token)
    // =========================================================================
    Route::post(
        '/auth/login',
        [AuthController::class, 'login']
    );

    // =========================================================================
    // Endpoint privat (semua butuh token Sanctum)
    // =========================================================================
    Route::middleware('auth:sanctum')->group(function (): void {

        // =========================================================================
        // AUTH
        // =========================================================================
        Route::post(
            '/auth/logout',
            [AuthController::class, 'logout']
        );

        Route::get(
            '/auth/me',
            [AuthController::class, 'me']
        );

        // =========================================================================
        // Lokasi sekolah — guru & admin
        // =========================================================================
        Route::prefix('locations')->group(function (): void {

            Route::get(
                '/',
                [LocationController::class, 'index']
            );

            Route::get(
                '/nearest',
                [LocationController::class, 'nearest']
            );

            Route::get(
                '/{location}',
                [LocationController::class, 'show']
            );
        });

        // =========================================================================
        // Absensi — guru (teacher)
        // =========================================================================
        Route::prefix('presence')->group(function (): void {

            Route::get(
                '/today',
                [PresenceController::class, 'today']
            );

            Route::get(
                '/history',
                [PresenceController::class, 'history']
            );

            Route::get(
                '/summary',
                [PresenceController::class, 'summary']
            );

            Route::post(
                '/check-in',
                [PresenceController::class, 'checkIn']
            );

            Route::post(
                '/check-out',
                [PresenceController::class, 'checkOut']
            );

            Route::post(
                '/izin',
                [PresenceController::class, 'izin']
            );

            Route::post(
                '/sakit',
                [PresenceController::class, 'sakit']
            );
        });

        // =========================================================================
        // Penggajian — guru (teacher)
        // =========================================================================
        Route::prefix('payroll')->group(function (): void {

            Route::get(
                '/me',
                [PayrollController::class, 'mySlip']
            );

            Route::get(
                '/history',
                [PayrollController::class, 'myHistory']
            );
        });

        // =========================================================================
        // Jadwal Mengajar — guru (teacher)
        // =========================================================================
        Route::prefix('schedules')->group(function (): void {

            Route::get(
                '/',
                [ScheduleController::class, 'index']
            );
        });

        // =========================================================================
        // Penilaian Guru — mobile teacher
        // =========================================================================
        Route::prefix('assessments')->group(function (): void {

            Route::get(
                '/me',
                [AssessmentController::class, 'myAssessments']
            );
        });

        // =========================================================================
        // Admin / Kepala Sekolah — proteksi api.admin
        // =========================================================================
        Route::middleware('api.admin')
            ->prefix('admin')
            ->group(function (): void {

                // =========================================================================
                // Review absensi
                // =========================================================================
                Route::prefix('presences')->group(function (): void {

                    Route::get(
                        '/',
                        [AdminPresenceController::class, 'index']
                    );

                    Route::get(
                        '/{presence}',
                        [AdminPresenceController::class, 'show']
                    );
                });

                // =========================================================================
                // Data guru
                // =========================================================================
                Route::prefix('teachers')->group(function (): void {

                    Route::get(
                        '/',
                        [AdminPresenceController::class, 'teachers']
                    );

                    Route::get(
                        '/{user}',
                        [AdminPresenceController::class, 'teacherShow']
                    );

                    Route::get(
                        '/{user}/presences',
                        [AdminPresenceController::class, 'teacherPresences']
                    );
                });

                // =========================================================================
                // Rekap periodik
                // =========================================================================
                Route::prefix('recap')->group(function (): void {

                    Route::get(
                        '/daily',
                        [AdminRecapController::class, 'daily']
                    );

                    Route::get(
                        '/weekly',
                        [AdminRecapController::class, 'weekly']
                    );

                    Route::get(
                        '/monthly',
                        [AdminRecapController::class, 'monthly']
                    );

                    Route::get(
                        '/teachers/{user}',
                        [AdminRecapController::class, 'teacherDetail']
                    );
                });

                // =========================================================================
                // Penggajian admin
                // =========================================================================
                Route::prefix('payroll')->group(function (): void {

                    Route::get(
                        '/',
                        [PayrollController::class, 'index']
                    );

                    Route::get(
                        '/{salary}',
                        [PayrollController::class, 'show']
                    );

                    Route::post(
                        '/generate',
                        [PayrollController::class, 'generate']
                    );

                    Route::post(
                        '/{salary}/approve',
                        [PayrollController::class, 'approve']
                    );

                    Route::post(
                        '/{salary}/paid',
                        [PayrollController::class, 'markAsPaid']
                    );

                    Route::post(
                        '/{salary}/revert',
                        [PayrollController::class, 'revert']
                    );
                });

                // =========================================================================
                // Penilaian Kinerja Guru
                // =========================================================================
                Route::prefix('assessments')->group(function (): void {

                    Route::get(
                        '/',
                        [AssessmentController::class, 'index']
                    );

                    Route::post(
                        '/',
                        [AssessmentController::class, 'store']
                    );

                    Route::get(
                        '/unassessed-teachers',
                        [AssessmentController::class, 'unassessedTeachers']
                    );

                    Route::get(
                        '/{assessment}',
                        [AssessmentController::class, 'show']
                    );

                    Route::put(
                        '/{assessment}',
                        [AssessmentController::class, 'update']
                    );

                    Route::delete(
                        '/{assessment}',
                        [AssessmentController::class, 'destroy']
                    );
                });

                // =========================================================================
                // CRUD Guru
                // =========================================================================
                Route::apiResource(
                    'teachers',
                    TeacherController::class
                )->parameters([
                            'teachers' => 'user'
                        ]);
            });
    });
});
