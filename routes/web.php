<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ClassGenerateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileContrller;
use App\Http\Controllers\Admin\GraduationCeremonyController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\QuitController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarningController;
use App\Http\Controllers\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::post('/logout', [AuthenticateController::class, 'logout'])->name('handleLogout');
Route::get('/auth/redirect', [AuthenticateController::class, 'redirectToSSO'])->name('sso.redirect');
Route::get('/auth/callback', [AuthenticateController::class, 'handleCallback'])->name('sso.callback');

Route::middleware('auth.sso')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('check.faculty')->group(function (): void {
        Route::resource('users', UserController::class)->only(['index', 'show']);
        Route::resource('students', StudentController::class)->only(['index', 'show', 'edit', 'destroy']);
        Route::get('students/{student}/edit-detail', [StudentController::class, 'editDetail'])->name('students.edit-detail');
        Route::resource('classes', ClassGenerateController::class);
        Route::get('classes-teacher', [ClassGenerateController::class, 'getClassTeacher'])->name('classes-teacher');
        Route::get('classes-sub-teacher', [ClassGenerateController::class, 'getClassSubTeacher'])->name('classes-sub-teacher');
        Route::get('classes-import-teacher-assignment', [ClassGenerateController::class, 'importTeacherAssignment'])->name('classes.import-teacher-assignment');
        Route::get('classes-import-specialized-transfer', [ClassGenerateController::class, 'importSpecializedTransfer'])->name('classes.import-specialized-transfer');
        Route::prefix('students')->group(function (): void {
            Route::get('/import/{admission_year}/admission-year', [StudentController::class, 'import'])->name('students.import');
        });
        Route::resource('roles', RoleController::class)->only(['index', 'create', 'edit']);
        Route::resource('posts', PostController::class);
        Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');

        // Graduation Ceremony Management
        Route::get('graduation', [GraduationCeremonyController::class, 'index'])->name('graduation.index');
        Route::get('graduation/create', [GraduationCeremonyController::class, 'create'])->name('graduation.create');
        Route::get('graduation/{ceremony}/edit', [GraduationCeremonyController::class, 'edit'])->name('graduation.edit');
        Route::get('graduation/{ceremony}', [GraduationCeremonyController::class, 'show'])->name('graduation.show');
        Route::get('graduation/{ceremony}/import', [GraduationCeremonyController::class, 'import'])->name('graduation.import');

        // Warning Management
        Route::get('warnings', [WarningController::class, 'index'])->name('warnings.index');
        Route::get('warnings/create', [WarningController::class, 'create'])->name('warnings.create');
        Route::get('warnings/{warning}/edit', [WarningController::class, 'edit'])->name('warnings.edit');
        Route::get('warnings/{warning}', [WarningController::class, 'show'])->name('warnings.show');
        Route::get('warnings/{warning}/import', [WarningController::class, 'import'])->name('warnings.import');

        // Quit Management
        Route::get('quits', [QuitController::class, 'index'])->name('quits.index');
        Route::get('quits/create', [QuitController::class, 'create'])->name('quits.create');
        Route::get('quits/{quit}/edit', [QuitController::class, 'edit'])->name('quits.edit');
        Route::get('quits/{quit}', [QuitController::class, 'show'])->name('quits.show');
        Route::get('quits/{quit}/import', [QuitController::class, 'import'])->name('quits.import');
    });

    Route::get('/download-template/{name}', [FileContrller::class, 'downloadFileTemplateImport'])->name('file.download-template');
});
