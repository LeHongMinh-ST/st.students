<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SsoService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(
        private SsoService $ssoService
    ) {

    }


    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', User::class);

        return view('pages.user.index');
    }


    public function show(User $user): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $user);

        $user->load('userRoles');

        $response = $this->ssoService->get('/api/users/' . $user->sso_id);

        $userData = $response['data'];

        return view('pages.user.show', compact('user', 'userData'));
    }
}
