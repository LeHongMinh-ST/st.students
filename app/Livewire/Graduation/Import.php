<?php

declare(strict_types=1);

namespace App\Livewire\Graduation;

use App\Enums\StatusImport;
use App\Enums\TypeImport;
use App\Imports\GraduationStudentPreviewImport;
use App\Jobs\ImportGraduationStudentsJob;
use App\Models\GraduationCeremony;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;
    use WithPagination;

    public GraduationCeremony $ceremony;
    public $file;
    public array $previewData = [];
    public bool $showPreview = false;

    public function mount(GraduationCeremony $ceremony): void
    {
        $this->ceremony = $ceremony;
    }

    public function render()
    {
        return view('livewire.graduation.import');
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $this->previewData = [];
        $this->showPreview = false;

        try {
            $import = new GraduationStudentPreviewImport();
            $this->previewData = Excel::toArray($import, $this->file)[0];

            // Remove header row
            array_shift($this->previewData);

            // Format preview data
            $this->previewData = array_map(fn ($row, $index) => [
                'stt' => $index + 1,
                'ma_sv' => $row[0] ?? '',
                'ho_ten' => $row[1] ?? '',
                'email' => $row[2] ?? '',
                'gpa' => $row[3] ?? '',
                'xep_loai' => $row[4] ?? '',
            ], $this->previewData, array_keys($this->previewData));

            $this->showPreview = true;
        } catch (Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi đọc file: ' . $e->getMessage());
        }
    }

    #[On('onImportFile')]
    public function import($fileName, $filePath): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();
        $importHistory = ImportHistory::create([
            'file_name' => $fileName,
            'path' => $filePath,
            'status' => StatusImport::Pending,
            'total_records' => count($this->previewData),
            'successful_records' => 0,
            'faculty_id' => $facultyId,
            'type' => TypeImport::StudentGraduate,
            'created_by' => Auth::id(),
            'admission_year_id' => 0 // Not needed for graduation import
        ]);

        dispatch(new ImportGraduationStudentsJob(Auth::id(), $importHistory->id, $this->ceremony->id));
        $this->dispatch('onOpenProcessModal');
    }

    public function startImport(): void
    {
        if (!$this->file) {
            session()->flash('error', 'Vui lòng chọn file để import.');
            return;
        }

        try {
            $path = $this->file->store('imports');
            $this->dispatch('onImportFile', fileName: $this->file->getClientOriginalName(), filePath: $path);
        } catch (Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi lưu file: ' . $e->getMessage());
        }
    }
}
