<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentStatus;
use App\Helpers\Constants;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Student $student;

    public string $tab = 'profile';

    public bool $editStatusMode = false;

    public StudentStatus $studentStatus = StudentStatus::CurrentlyStudying;

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->studentStatus = $student->status;
    }

    public function updatedStudentStatus(): void
    {
        $this->student->status = $this->studentStatus;
        $this->student->save();
        $this->editStatusMode = false;
    }

    public function render()
    {
        $classes = $this->student->classes()
            ->paginate(Constants::PER_PAGE);

        $families = $this->student->families;

        return view('livewire.student.show', [
            'classes' => $classes,
            'families' => $families
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }
}
