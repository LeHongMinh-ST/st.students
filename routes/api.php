<?php

declare(strict_types=1);

use App\Http\Controllers\Api\External\AdmissionYearController;
use App\Http\Controllers\Api\External\ClassGenerateController;
use App\Http\Controllers\Api\External\GraduationCeremonyController;
use App\Http\Controllers\Api\External\StudentController;
use App\Http\Controllers\Api\External\TrainingIndustryController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:api');
Route::post('/verify', function (Request $request) {
    $tokenSSO = $request->access_token;
    Log::info('Verifying token: ' . $tokenSSO);
    try {
        $response = Http::withToken($tokenSSO)->get(config('auth.sso.ip') . '/api/user');
        if ($response->successful()) {
            $userData = $response->json();
            $user = User::where('faculty_id', $userData['faculty_id'])->where('sso_id', $userData['id'])->first();
            Log::debug('User: ' . $user);

            if ($user) {

                $localToken = $user->createToken('local_access')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Token is valid.',
                    'token' => $localToken,
                    'user' => $userData
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'error' => 'Unauthorized'
            ], 401);
        }
    } catch (Throwable $th) {
        Log::error('Failed to fetch user data from API: ' . $th->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Token is not provided or invalid.',
            'error' => 'Unauthorized'
        ], 401);
    }
});

// External API routes (cần authentication)
Route::prefix('v1/external')->middleware([
    'api',
    'auth:api'
])->group(function (): void {
    Route::prefix('/training-industries')->group(function (): void {
        Route::get('/', [TrainingIndustryController::class, 'index']);
    });

    Route::prefix('/graduation-ceremonies')->group(function (): void {
        Route::get('/', [GraduationCeremonyController::class, 'index']);
        Route::get('/{id}', [GraduationCeremonyController::class, 'show']);
        Route::get('/{id}/students', [GraduationCeremonyController::class, 'students']);
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
