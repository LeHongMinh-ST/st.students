<?php

declare(strict_types=1);

use App\Http\Middleware\AuthenticateSSO;
use App\Http\Middleware\CheckFaculty;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        channels: __DIR__ . '/../routes/channels.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.sso' => AuthenticateSSO::class,
            'check.faculty' => CheckFaculty::class,
            'api.client' => CheckClientCredentials::class,
        ])->trustProxies('*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is not provided or invalid.',
                    'error' => 'Unauthorized'
                ], 401);
            }
        });
    })->create();
