<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use Livewire\Component;

class StudentList extends Component
{
    public function render()
    {
        return view('livewire.student.student-list');
    }
}
