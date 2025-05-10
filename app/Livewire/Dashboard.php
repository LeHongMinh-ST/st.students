<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StudentStatus;
use App\Models\ClassGenerate;
use App\Models\Student;
use App\Services\SsoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalStudents = 0;
    public int $graduatedStudents = 0;
    public int $warnedStudents = 0;
    public int $totalClasses = 0;

    public bool $canViewTotalStudents = false;
    public bool $canViewGraduatedStudents = false;
    public bool $canViewWarnedStudents = false;
    public bool $canViewTotalClasses = false;

    public function mount(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();
        $user = Auth::user();

        // Kiểm tra quyền xem từng loại thống kê
        $this->canViewTotalStudents = $user->isAdmin() || $user->hasPermission('dashboard.students');
        $this->canViewGraduatedStudents = $user->isAdmin() || $user->hasPermission('dashboard.graduated');
        $this->canViewWarnedStudents = $user->isAdmin() || $user->hasPermission('dashboard.warned');
        $this->canViewTotalClasses = $user->isAdmin() || $user->hasPermission('dashboard.classes');

        // Get total students
        if ($this->canViewTotalStudents) {
            $this->totalStudents = Student::where('faculty_id', $facultyId)->count();
        }

        // Get graduated students
        if ($this->canViewGraduatedStudents) {
            $this->graduatedStudents = Student::where('faculty_id', $facultyId)
                ->where('status', StudentStatus::Graduated)
                ->count();
        }

        // Get warned students (students with warnings in the last year)
        if ($this->canViewWarnedStudents) {
            $studentIds = Student::where('faculty_id', $facultyId)->pluck('id')->toArray();
            if (!empty($studentIds)) {
                $this->warnedStudents = DB::table('student_warnings')
                    ->whereIn('student_id', $studentIds)
                    ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                    ->distinct('student_id')
                    ->count('student_id');
            }
        }

        // Get total classes
        if ($this->canViewTotalClasses) {
            $this->totalClasses = ClassGenerate::where('faculty_id', $facultyId)->count();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
