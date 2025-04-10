<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quit;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class QuitController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Quit::class);

        return view('pages.quit.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Quit::class);

        return view('pages.quit.create');
    }

    public function edit(Quit $quit): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $quit);

        return view('pages.quit.edit', compact('quit'));
    }

    public function show(Quit $quit): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $quit);

        return view('pages.quit.show', compact('quit'));
    }

    public function import(Quit $quit): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $quit);

        return view('pages.quit.import', compact('quit'));
    }
}
