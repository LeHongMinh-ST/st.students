<?php

declare(strict_types=1);

use App\Http\Middleware\AuthenticateSSO;
use App\Http\Middleware\CheckFaculty;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
        ])->trustProxies('*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    })->create();
