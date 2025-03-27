<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileContrller;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
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
        Route::prefix('students')->group(function (): void {
            Route::get('/import/{admission_year}/admission-year', [StudentController::class, 'import'])->name('students.import');
        });
        Route::resource('roles', RoleController::class)->only(['index','create','edit']);
    });

    Route::get('/download-template/{name}', [FileContrller::class, 'downloadFileTemplateImport'])->name('file.download-template');
});
