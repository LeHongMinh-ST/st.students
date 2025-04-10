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

    public function render()
    {
        $studentsQuery = $this->class->students()
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            });

        // Filter by tab
        switch ($this->tab) {
            case 'studying':
                $studentsQuery->where('status', StudentStatus::CurrentlyStudying);
                break;
            case 'graduated':
                $studentsQuery->where('status', StudentStatus::Graduated);
                break;
            case 'deferred':
                $studentsQuery->where('status', StudentStatus::Deferred);
                break;
            case 'dropped':
                $studentsQuery->whereIn('status', [
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

        // Find class teacher (giáo viên chủ nhiệm)
        $classAssign = ClassAssign::where('class_id', $this->class->id)
            ->with('teacher')
            ->first();

        $this->classTeacher = $classAssign ? [
            'id' => $classAssign->teacher->id ?? null,
            'name' => $classAssign->teacher->full_name ?? 'Chưa phân công'
        ] : ['id' => null, 'name' => 'Chưa phân công'];

        // Find class sub-teacher (cố vấn học tập)
        $this->classSubTeacher = $classAssign && $classAssign->subTeacher ? [
            'id' => $classAssign->subTeacher->id ?? null,
            'name' => $classAssign->subTeacher->full_name ?? 'Chưa phân công'
        ] : ['id' => null, 'name' => 'Chưa phân công'];
    }

    private function loadClassStatistics(): void
    {
        // Get total students
        $this->totalStudents = $this->class->students()->count();

        // Get currently studying students
        $this->currentlyStudying = $this->class->students()
            ->where('status', StudentStatus::CurrentlyStudying)
            ->count();

        // Get graduated students
        $this->graduated = $this->class->students()
            ->where('status', StudentStatus::Graduated)
            ->count();

        // Get deferred students
        $this->deferred = $this->class->students()
            ->where('status', StudentStatus::Deferred)
            ->count();

        // Get dropped students (combined all drop statuses)
        $this->dropped = $this->class->students()
            ->whereIn('status', [
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
