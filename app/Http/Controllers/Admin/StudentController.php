<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        return view('pages.student.index');
    }
}
