<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Helpers\Constants;
use App\Models\ClassAssign;
use App\Models\ClassGenerate;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public ClassGenerate $class;

    #[Url(as: 'q')]
    public string $search = '';

    public string $tab = 'students';

    // Class statistics
    public int $totalStudents = 0;
    public int $currentlyStudying = 0;
    public int $graduated = 0;
    public int $deferred = 0;
    public int $dropped = 0;
    public int $warned = 0;

    // Class roles
    public ?Student $classPresident = null;
    public ?Student $classSecretary = null;
    public ?array $classTeacher = null;
    public ?array $classSubTeacher = null;
    public ?string $majorName = null;

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->loadClassRoles();
        $this->loadClassStatistics();
        $this->loadMajorInfo();
    }

    #[On('teacher-assignment-updated')]
    public function refreshClassRoles(): void
    {
        $this->loadClassRoles();
    }

    public function render()
    {
        $studentsQuery = $this->class->students()
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            });

        // Filter by tab
        switch ($this->tab) {
            case 'studying':
                $studentsQuery->where('students.status', StudentStatus::CurrentlyStudying);
                break;
            case 'graduated':
                $studentsQuery->where('students.status', StudentStatus::Graduated);
                break;
            case 'deferred':
                $studentsQuery->where('students.status', StudentStatus::Deferred);
                break;
            case 'dropped':
                $studentsQuery->whereIn('students.status', [
                    StudentStatus::ToDropOut,
                    StudentStatus::TemporarilySuspended,
                    StudentStatus::Expelled
                ]);
                break;
            case 'warned':
                $classStudentIds = $this->class->students()->pluck('students.id')->toArray();
                if (!empty($classStudentIds)) {
                    $warnedStudentIds = DB::table('student_warnings')
                        ->whereIn('student_id', $classStudentIds)
                        ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                        ->distinct('student_id')
                        ->pluck('student_id')
                        ->toArray();
                    $studentsQuery->whereIn('students.id', $warnedStudentIds);
                }
                break;
            default: // 'students' tab or any other
                // No additional filtering
                break;
        }

        $students = $studentsQuery->paginate(Constants::PER_PAGE);
        return view('livewire.class.show', [
            'students' => $students
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    private function loadClassRoles(): void
    {
        // Find class president (lớp trưởng)
        $this->classPresident = $this->class->students()
            ->wherePivot('role', StudentRole::President)
            ->first();

        // Find class secretary (bí thư)
        $this->classSecretary = $this->class->students()
            ->wherePivot('role', StudentRole::Secretary)
            ->first();

        // Find active class assignment (giáo viên chủ nhiệm & cố vấn học tập)
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $academicYear = $currentYear . '-' . $nextYear;

        // First try to find an assignment for the current academic year
        $classAssign = ClassAssign::where('class_id', $this->class->id)
            ->where('year', $academicYear)
            ->where('status', 'active')
            ->with(['teacher', 'subTeacher'])
            ->first();

        // If not found, get the most recent active assignment
        if (!$classAssign) {
            $classAssign = ClassAssign::where('class_id', $this->class->id)
                ->where('status', 'active')
                ->with(['teacher', 'subTeacher'])
                ->orderBy('year', 'desc')
                ->first();
        }

        // If still not found, get any assignment
        if (!$classAssign) {
            $classAssign = ClassAssign::where('class_id', $this->class->id)
                ->with(['teacher', 'subTeacher'])
                ->orderBy('year', 'desc')
                ->first();
        }

        // Set class teacher info
        $this->classTeacher = $classAssign && $classAssign->teacher ? [
            'id' => $classAssign->teacher->id,
            'name' => $classAssign->teacher->full_name ?? $classAssign->teacher->name
        ] : ['id' => null, 'name' => 'Chưa phân công'];

        // Set class sub-teacher info
        $this->classSubTeacher = $classAssign && $classAssign->subTeacher ? [
            'id' => $classAssign->subTeacher->id,
            'name' => $classAssign->subTeacher->full_name ?? $classAssign->subTeacher->name
        ] : ['id' => null, 'name' => 'Chưa phân công'];
    }

    private function loadClassStatistics(): void
    {
        // Get total students
        $this->totalStudents = $this->class->students()->count();

        // Get currently studying students
        $this->currentlyStudying = $this->class->students()
            ->where('students.status', StudentStatus::CurrentlyStudying)
            ->count();

        // Get graduated students
        $this->graduated = $this->class->students()
            ->where('students.status', StudentStatus::Graduated)
            ->count();

        // Get deferred students
        $this->deferred = $this->class->students()
            ->where('students.status', StudentStatus::Deferred)
            ->count();

        // Get dropped students (combined all drop statuses)
        $this->dropped = $this->class->students()
            ->whereIn('students.status', [
                StudentStatus::ToDropOut,
                StudentStatus::TemporarilySuspended,
                StudentStatus::Expelled
            ])
            ->count();

        // Get warned students (students with warnings in the last 2 semesters)
        $classStudentIds = $this->class->students()->pluck('students.id')->toArray();

        if (!empty($classStudentIds)) {
            $this->warned = DB::table('student_warnings')
                ->whereIn('student_id', $classStudentIds)
                ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
                ->distinct('student_id')
                ->count('student_id');
        }
    }

    private function loadMajorInfo(): void
    {
        // Get major name if available
        if ($this->class->marjor_id) { // Note: The field is spelled 'marjor_id' in the migration
            $major = DB::table('majors')->where('id', $this->class->marjor_id)->first();
            $this->majorName = $major ? $major->name : null;
        }
    }
}
