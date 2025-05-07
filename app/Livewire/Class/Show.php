<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\StudentRole;
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

    #[Url(as: 'tab')]
    public string $tab = 'info';

    // Tab management only

    // Class roles
    public ?Student $classPresident = null;
    public ?Student $classVicePresident = null;
    public ?Student $classSecretary = null;
    public ?Student $classViceSecretary = null;
    public ?array $classTeacher = null;
    public ?array $classSubTeacher = null;
    public ?string $majorName = null;

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->loadClassRoles();
        $this->loadMajorInfo();
    }

    #[On('teacher-assignment-updated')]
    #[On('student-assignment-updated')]
    public function refreshClassRoles(): void
    {
        $this->loadClassRoles();
    }

    public function render()
    {
        return view('livewire.class.show');
    }

    public function setTab(string $tab): void
    {
        // Only handle main tabs (info, stats, teachers)
        $this->tab = $tab;
    }

    private function loadClassRoles(): void
    {
        // Find class president (lớp trưởng)
        $this->classPresident = $this->class->students()
            ->wherePivot('role', StudentRole::President)
            ->wherePivot('status', 'active')
            ->first();

        // Find class vice president (lớp phó)
        $this->classVicePresident = $this->class->students()
            ->wherePivot('role', StudentRole::VicePresident)
            ->wherePivot('status', 'active')
            ->first();

        // Find class secretary (bí thư)
        $this->classSecretary = $this->class->students()
            ->wherePivot('role', StudentRole::Secretary)
            ->wherePivot('status', 'active')
            ->first();

        // Find class vice secretary (phó bí thư)
        $this->classViceSecretary = $this->class->students()
            ->wherePivot('role', StudentRole::ViceSecretary)
            ->wherePivot('status', 'active')
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

        // Set class sub-teacher info (không còn sử dụng nhưng giữ lại để tránh lỗi)
        $this->classSubTeacher = ['id' => null, 'name' => 'Không áp dụng'];
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
