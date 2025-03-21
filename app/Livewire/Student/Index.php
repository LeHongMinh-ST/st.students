<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Models\AdmissionYear;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public AdmissionYear|null $admissionYear = null;

    public function render()
    {
        return view('livewire.student.index');
    }

    #[On('onSetAdmissionYear')]
    public function handleSetAdmissionYear($admissionYear): void
    {
        $this->admissionYear = AdmissionYear::where('admission_year', $admissionYear)->first();
    }
}
