<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StatusImport as StudentImportEnum;
use App\Enums\TypeImport;
use App\Helpers\Constants;
use App\Jobs\ImportStudentsJob;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Import extends Component
{
    use WithFileUploads;
    use WithoutUrlPagination;
    use WithPagination;

    public $previewData = [];
    public $admissionYear;

    public string $tab = 'import';

    public function setTab(string $tab): void
    {
        $this->tab = $tab ?? 'import';
    }

    public function mount($admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }

    #[On('onSetFileImport')]
    public function previewFile($previewData): void
    {
        $this->previewData = $previewData;
    }

    #[On('onClearPreviewData')]
    public function clearPreviewData(): void
    {
        $this->previewData = [];
    }

    #[On('onImportFile')]
    public function import($fileName, $filePath): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();
        $importHistory = ImportHistory::create([
            'file_name' => $fileName,
            'path' => $filePath,
            'status' => StudentImportEnum::Pending,
            'total_records' => count($this->previewData),
            'successful_records' => 0,
            'faculty_id' => $facultyId,
            'type' => TypeImport::Student,
            'created_by' => Auth::id(),
            'admission_year_id' => $this->admissionYear->id
        ]);
        dispatch(new ImportStudentsJob(Auth::id(), $importHistory->id, $this->admissionYear->id));
        $this->dispatch('onOpenProcessModal');
    }


    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();
        $histories = ImportHistory::query()
            ->where('faculty_id', $facultyId)
            ->where('type', TypeImport::Student)
            ->where('admission_year_id', $this->admissionYear->id)
            ->with('user')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.student.import', [
            'histories' => $histories
        ]);
    }
}
