<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\PresenceCrudController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssessmentCrudController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TeacherCrudController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return Auth::user()?->role === User::ROLE_ADMIN
        ? redirect()->route('admin.dashboard')
        : redirect()->route('dashboard');
});
// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::get('/admin/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');
    Route::get('/admin/presences', [AdminController::class, 'presences'])->name('admin.presences');
    Route::get('/admin/assessments', [AssessmentCrudController::class, 'index'])->name('admin.assessments.index');
    Route::get('/admin/assessments/create', [AssessmentCrudController::class, 'create'])->name('admin.assessments.create');
    Route::post('/admin/assessments', [AssessmentCrudController::class, 'store'])->name('admin.assessments.store');
    Route::get('/admin/assessments/{assessment}/edit', [AssessmentCrudController::class, 'edit'])->name('admin.assessments.edit');
    Route::put('/admin/assessments/{assessment}', [AssessmentCrudController::class, 'update'])->name('admin.assessments.update');
    Route::delete('/admin/assessments/{assessment}', [AssessmentCrudController::class, 'destroy'])->name('admin.assessments.destroy');
    Route::get('/admin/presences/export', [AdminController::class, 'exportPresences'])->name('admin.presences.export');

    // CRUD Absensi Admin
    Route::prefix('admin/presences/data')->name('admin.presences.crud.')->group(function () {
        Route::get('/', [PresenceCrudController::class, 'index'])->name('index');
        Route::get('/create', [PresenceCrudController::class, 'create'])->name('create');
        Route::post('/', [PresenceCrudController::class, 'store'])->name('store');
        Route::get('/{presence}', [PresenceCrudController::class, 'show'])->name('show');
        Route::get('/{presence}/edit', [PresenceCrudController::class, 'edit'])->name('edit');
        Route::put('/{presence}', [PresenceCrudController::class, 'update'])->name('update');
        Route::delete('/{presence}', [PresenceCrudController::class, 'destroy'])->name('destroy');
    });
    Route::get('/admin/teacher/{user}/detail', [DashboardController::class, 'teacherDetail'])->name('teacher.detail');
    Route::get('/admin/presence/{presence}/detail', [DashboardController::class, 'presenceDetail'])->name('presence.detail');
    Route::get('/admin/salary', [DashboardController::class, 'salary'])->name('admin.salary');

    Route::prefix('admin/teacher/{user}')->name('admin.teacher.')->group(function () {
        Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    });
    Route::prefix('admin/teachers')->name('admin.teachers.')->group(function () {
        Route::get('/create', [TeacherCrudController::class, 'create'])->name('create');
        Route::post('/store', [TeacherCrudController::class, 'store'])->name('store');
        Route::get('/{teacher}/edit', [TeacherCrudController::class, 'edit'])->name('edit');
        Route::put('/{teacher}/update', [TeacherCrudController::class, 'update'])->name('update');
        Route::delete('/{teacher}/delete', [TeacherCrudController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';

