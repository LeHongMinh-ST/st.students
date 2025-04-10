<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraduationCeremony;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class GraduationCeremonyController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', GraduationCeremony::class);

        return view('pages.graduation.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', GraduationCeremony::class);

        return view('pages.graduation.create');
    }

    public function edit(GraduationCeremony $ceremony): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $ceremony);

        return view('pages.graduation.edit', compact('ceremony'));
    }

    public function show(GraduationCeremony $ceremony): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $ceremony);

        return view('pages.graduation.show', compact('ceremony'));
    }

    public function import(GraduationCeremony $ceremony): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $ceremony);

        return view('pages.graduation.import', compact('ceremony'));
    }
}
