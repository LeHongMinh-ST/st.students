<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGenerate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ClassGenerateController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', ClassGenerate::class);

        return view('pages.class.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', ClassGenerate::class);

        return view('pages.class.create');
    }

    public function edit(ClassGenerate $class): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $class);

        return view('pages.class.edit', compact('class'));
    }

    public function show(ClassGenerate $class): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $class);

        return view('pages.class.show', compact('class'));
    }

    public function getClassTeacher(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('manageTeacher', ClassGenerate::class);

        return view('pages.class.class-teacher');
    }

    public function getClassSubTeacher(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('manageSubTeacher', ClassGenerate::class);

        return view('pages.class.class-sub-teacher');
    }

    public function importTeacherAssignment(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('manageTeacherAssignment', ClassGenerate::class);

        return view('pages.class.import-teacher-assignment');
    }
}
