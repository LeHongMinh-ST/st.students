<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\StudentStatus;
use App\Models\ClassGenerate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClassStatistics extends Component
{
    public ClassGenerate $class;

    // Class statistics
    public int $totalStudents = 0;
    public int $currentlyStudying = 0;
    public int $graduated = 0;
    public int $deferred = 0;
    public int $dropped = 0;
    public int $warned = 0;

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
        $this->loadClassStatistics();
    }

    public function render()
    {
        return view('livewire.class.class-statistics');
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
}
