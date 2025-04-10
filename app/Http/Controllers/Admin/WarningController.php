<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warning;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class WarningController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Warning::class);

        return view('pages.warning.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Warning::class);

        return view('pages.warning.create');
    }

    public function edit(Warning $warning): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $warning);

        return view('pages.warning.edit', compact('warning'));
    }

    public function show(Warning $warning): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $warning);

        return view('pages.warning.show', compact('warning'));
    }

    public function import(Warning $warning): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $warning);

        return view('pages.warning.import', compact('warning'));
    }
}
