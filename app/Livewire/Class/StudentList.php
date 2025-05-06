<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\StudentStatus;
use App\Helpers\Constants;
use App\Models\ClassGenerate;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public ClassGenerate $class;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'filter')]
    public string $filter = 'all';

    public function mount(ClassGenerate $class): void
    {
        $this->class = $class;
    }

    public function render()
    {
        $studentsQuery = $this->class->students()
            ->when($this->search, function ($query): void {
                $query->search($this->search);
            });

        // Apply student filters
        switch ($this->filter) {
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
            case 'all':
            default:
                // No additional filtering
                break;
        }

        $students = $studentsQuery->paginate(Constants::PER_PAGE);

        return view('livewire.class.student-list', [
            'students' => $students
        ]);
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }
}
