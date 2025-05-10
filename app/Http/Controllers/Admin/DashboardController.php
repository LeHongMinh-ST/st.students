<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isStudent()) {
            return redirect()->route('student.profile');
        }

        return view('pages.dashboard');
    }
}
