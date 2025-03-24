<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Helpers\Constants;
use App\Models\AdmissionYear;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public AdmissionYear|null $admissionYear = null;

    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $students = $this->admissionYear->students()
            ->search($this->search)
            ->paginate(Constants::PER_PAGE);

        return view('livewire.student.student-list', [
            'students' => $students
        ]);
    }

    public function mount(AdmissionYear $admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }
}
