<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StudentStatus;
use App\Models\ClassGenerate;
use App\Models\Student;
use App\Services\SsoService;
use DB;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalStudents = 0;
    public int $graduatedStudents = 0;
    public int $warnedStudents = 0;
    public int $totalClasses = 0;

    public function mount(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        // Get total students
        $this->totalStudents = Student::where('faculty_id', $facultyId)->count();

        // Get graduated students
        $this->graduatedStudents = Student::where('faculty_id', $facultyId)
            ->where('status', StudentStatus::Graduated)
            ->count();

        // Get warned students (students with warnings in the last year)
        $studentIds = Student::where('faculty_id', $facultyId)->pluck('id')->toArray();
        if (!empty($studentIds)) {
            $this->warnedStudents = DB::table('student_warnings')
                ->whereIn('student_id', $studentIds)
                ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                ->distinct('student_id')
                ->count('student_id');
        }

        // Get total classes
        $this->totalClasses = ClassGenerate::where('faculty_id', $facultyId)->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
