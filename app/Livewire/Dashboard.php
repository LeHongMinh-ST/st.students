<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StudentStatus;
use App\Enums\StudentUpdateStatus;
use App\Models\ClassGenerate;
use App\Models\LogActivity;
use App\Models\Student;
use App\Models\StudentUpdate;
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
    public bool $canViewRecentActivities = false;
    public bool $canViewPendingUpdates = false;

    public $recentActivities = [];
    public $pendingUpdates = [];

    public function mount(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();
        $user = Auth::user();

        // Kiểm tra quyền xem từng loại thống kê
        $this->canViewTotalStudents = $user->isAdmin() || $user->hasPermission('dashboard.students');
        $this->canViewGraduatedStudents = $user->isAdmin() || $user->hasPermission('dashboard.graduated');
        $this->canViewWarnedStudents = $user->isAdmin() || $user->hasPermission('dashboard.warned');
        $this->canViewTotalClasses = $user->isAdmin() || $user->hasPermission('dashboard.classes');
        $this->canViewRecentActivities = $user->isAdmin() || $user->hasPermission('activity.index');
        $this->canViewPendingUpdates = $user->isAdmin() || $user->hasPermission('student.update.index') ||
            $user->hasPermission('student.update.approve') || $this->isTeacher($user) || $this->isClassMonitor($user);

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

        // Get recent activities
        if ($this->canViewRecentActivities) {
            $this->recentActivities = LogActivity::where('faculty_id', $facultyId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Get pending updates
        if ($this->canViewPendingUpdates) {
            $query = StudentUpdate::with('student');

            if ($user->isAdmin() || $user->hasPermission('student.update.index')) {
                // Admin can see all pending updates
                $query->where('status', StudentUpdateStatus::TeacherApproved->value);
            } elseif ($this->isTeacher($user)) {
                // Teacher can see updates approved by class monitor
                $classIds = $this->getClassIdsWhereUserIsTeacher($user);
                $studentIds = DB::table('class_students')
                    ->whereIn('class_id', $classIds)
                    ->pluck('student_id')
                    ->toArray();

                $query->whereIn('student_id', $studentIds)
                    ->where('status', StudentUpdateStatus::ClassOfficerApproved->value);
            } elseif ($this->isClassMonitor($user)) {
                // Class monitor can see pending updates from their class
                $classIds = $this->getClassIdsWhereUserIsMonitor($user);
                $studentIds = DB::table('class_students')
                    ->whereIn('class_id', $classIds)
                    ->pluck('student_id')
                    ->toArray();

                $query->whereIn('student_id', $studentIds)
                    ->where('status', StudentUpdateStatus::Pending->value);
            }

            $this->pendingUpdates = $query->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    /**
     * Check if user is a teacher for any class
     */
    private function isTeacher($user): bool
    {
        return $user->hasPermission('class.teacher') && DB::table('class_assigns')
            ->where('teacher_id', $user->id)
            ->exists();
    }

    /**
     * Check if user is a class monitor for any class
     */
    private function isClassMonitor($user): bool
    {
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return false;
        }

        return DB::table('class_students')
            ->where('student_id', $student->id)
            ->where('role', 'class_monitor')
            ->exists();
    }

    /**
     * Get class IDs where user is a teacher
     */
    private function getClassIdsWhereUserIsTeacher($user): array
    {
        return DB::table('class_assigns')
            ->where('teacher_id', $user->id)
            ->pluck('class_id')
            ->toArray();
    }

    /**
     * Get class IDs where user is a class monitor
     */
    private function getClassIdsWhereUserIsMonitor($user): array
    {
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return [];
        }

        return DB::table('class_students')
            ->where('student_id', $student->id)
            ->where('role', 'class_monitor')
            ->pluck('class_id')
            ->toArray();
    }
}
