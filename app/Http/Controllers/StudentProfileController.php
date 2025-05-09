<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function show()
    {
        if (!Auth::user()->isStudent()) {
            return redirect()->route('dashboard');
        }

        $student = Student::where('user_id', Auth::id())->firstOrFail();

        return view('student.profile', [
            'student' => $student
        ]);
    }
}
