<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        return view('pages.role.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        return view('pages.role.create');
    }

    public function edit($role): View|Application|Factory|RedirectResponse
    {
        return view('pages.role.edit', compact('role'));
    }
}
