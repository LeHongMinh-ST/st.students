<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::post('/logout', [AuthenticateController::class, 'logout'])->name('handleLogout');
Route::get('/auth/redirect', [AuthenticateController::class, 'redirectToSSO'])->name('sso.redirect');
Route::get('/auth/callback', [AuthenticateController::class, 'handleCallback'])->name('sso.callback');

Route::middleware('auth.sso')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('check.faculty')->group(function () {
        //
    });
});
