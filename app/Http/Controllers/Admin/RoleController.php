<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Role::class);

        return view('pages.role.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Role::class);

        return view('pages.role.create');
    }

    public function edit(Role $role): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $role);

        return view('pages.role.edit', compact('role'));
    }
}
