<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Jobs\ImportStudentsJob;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Import extends Component
{
    use WithFileUploads;

    public $file;
    public $previewData = [];
    public $admissionYear;

    public function mount($admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }

    #[On('onSetFileImport')]
    public function previewFile($previewData): void
    {
        $this->previewData = $previewData;
    }

    #[On('onImportFile')]
    public function import($importHistoryId): void
    {
        dispatch(new ImportStudentsJob(Auth::id(), $importHistoryId, $this->admissionYear->id));
    }


    public function render()
    {
        return view('livewire.student.import');
    }
}
