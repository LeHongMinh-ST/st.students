<?php

declare(strict_types=1);

use App\Http\Controllers\Api\External\AdmissionYearController;
use App\Http\Controllers\Api\External\ClassGenerateController;
use App\Http\Controllers\Api\External\GraduationCeremonyController;
use App\Http\Controllers\Api\External\StudentController;
use App\Http\Controllers\Api\External\TrainingIndustryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:api');

// External API routes (cần authentication)
Route::prefix('v1/external')->middleware([
    'api',
    'api.client'
])->group(function (): void {
    Route::prefix('/training-industries')->group(function (): void {
        Route::get('/', [TrainingIndustryController::class, 'index']);
    });

    Route::prefix('/graduation-ceremonies')->group(function (): void {
        Route::get('/', [GraduationCeremonyController::class, 'index']);
    });

    Route::prefix('/classes')->group(function (): void {
        Route::get('/', [ClassGenerateController::class, 'index']);
    });

    // admission_year
    Route::prefix('/admission-years')->group(function (): void {
        Route::get('/', [AdmissionYearController::class, 'index']);
    });

    // students
    Route::prefix('/students')->group(function (): void {
        // student in graduation ceremony and faculty
        Route::get('/', [StudentController::class, 'index']);
    });
});
