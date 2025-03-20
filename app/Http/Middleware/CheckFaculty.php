<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Services\SsoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckFaculty
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::Normal->value) {
            abort(403);
        }

        $facultyId = $userData['role'] === Role::SuperAdmin->value
            ? Session::get('facultyId')
            : $userData['faculty_id'] ?? null;

        if (!$facultyId) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
