<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionYear;
use App\Models\Student;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        return view('pages.student.index');
    }

    public function import(AdmissionYear $admissionYear): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Student::class);

        return view('pages.student.import', compact('admissionYear'));
    }

    public function show(Student $student): View|Application|Factory|RedirectResponse
    {
        return view('pages.student.show', compact('student'));
    }
}
