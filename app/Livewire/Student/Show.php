<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StudentStatus;
use App\Models\Student;
use Livewire\Component;

class Show extends Component
{
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
        return view('livewire.student.show');
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }
}
