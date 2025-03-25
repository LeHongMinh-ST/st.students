<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Imports\StudentPreviewImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $file;
    public $fileName = "Kéo & thả file vào đây hoặc click để chọn";
    public $previewData = [];
    public $previewLoading = false;

    public function updatedFile(): void
    {
        $this->fileName = $this->file ? $this->file->getClientOriginalName() : "Kéo & thả file vào đây hoặc click để chọn";

        // Gọi preview ngay khi chọn file
        $this->previewFile();
    }

    public function resetFile(): void
    {
        $this->file = null;
        $this->fileName = "Kéo & thả file vào đây hoặc click để chọn";
        $this->previewData = [];
    }

    public function previewFile(): void
    {
        if (!$this->file) {
            return;
        }

        $this->previewLoading = true;

        $import = new StudentPreviewImport();
        Excel::import($import, $this->file);

        $this->previewData = $import->data;

        $this->previewLoading = false;
    }

    public function render()
    {
        return view('livewire.student.import');
    }
}
