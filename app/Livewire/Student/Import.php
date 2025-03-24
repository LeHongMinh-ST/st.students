<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Models\AdmissionYear;
use Livewire\Component;
use Livewire\WithFileUploads;

class Import extends Component
{
    use WithFileUploads;

    public AdmissionYear|null $admissionYear = null;

    public $file;

    public $fileName = "Kéo & thả file vào đây hoặc click để chọn";

    public function updatedFile(): void
    {
        if ($this->file) {
            $this->fileName = $this->file->getClientOriginalName();
        } else {
            $this->fileName = "Kéo & thả file vào đây hoặc click để chọn";
        }
    }


    public function render()
    {
        return view('livewire.student.import');
    }

    public function mount(AdmissionYear $admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }
}
