<?php

declare(strict_types=1);

namespace App\Livewire\Student;

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

    public $file;
    public $previewData = [];
    public $admissionYear;

    public string $tab = 'import';

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
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

    #[On('onImportFile')]
    public function import($importHistoryId): void
    {
        dispatch(new ImportStudentsJob(Auth::id(), $importHistoryId, $this->admissionYear->id));
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
