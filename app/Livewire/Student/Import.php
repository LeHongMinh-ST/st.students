<?php

declare(strict_types=1);

namespace App\Livewire\Student;

use App\Enums\StatusImport as StudentImportEnum;
use App\Enums\TypeImport;
use App\Events\ImportFinished;
use App\Imports\StudentPreviewImport;
use App\Jobs\ImportStudentsJob;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $file;
    public $fileName = "Kéo & thả tệp vào đây hoặc click để chọn";
    public $previewData = [];
    public $importProgress = 0;
    public $importTotal = 0;
    public $importCompleted = false;
    public $importErrors = [];
    public $importSuccessCount = 0;
    public $userId;
    public function mount(): void
    {
        $this->userId = Auth::id();
    }
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

        $import = new StudentPreviewImport();
        Excel::import($import, $this->file);

        $this->previewData = $import->data;

    }

    public function import(): void
    {
        if (!$this->file) {
            return;
        }
        DB::beginTransaction();
        try {

            $facultyId = app(SsoService::class)->getFacultyId();

            $path = $this->file->store(path: 'imports');

            $importHistory = ImportHistory::create([
                'file_name' => $this->fileName,
                'path' => $path,
                'status' => StudentImportEnum::Pending,
                'total_records' => count($this->previewData),
                'successful_records' => 0,
                'faculty_id' => $facultyId,
                'type' => TypeImport::Student,
                'created_by' => Auth::id(),
            ]);

            dispatch(new ImportStudentsJob(Auth::id(), $importHistory->id));
            DB::commit();
            $this->dispatch('onOpenProcessModal');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function testEvent(): void
    {
        Log::info('test event');
        event(new ImportFinished(Auth::id(), 1, StudentImportEnum::Completed, 1, 1, [], '00:00:00'));
    }

    public function render()
    {
        return view('livewire.student.import');
    }


    #[On('echo:import.progress.{userId},ImportStarted')]
    public function handleImportStarted($payload): void
    {
        Log::info('started');
        $this->importTotal = $payload['totalRecords'];
        $this->importProgress = 0;
        $this->importCompleted = false;
        $this->importErrors = [];
        $this->importSuccessCount = 0;
    }

    #[On('echo:import.progress.{userId},ImportProgressUpdated')]
    public function handleProgressUpdated($payload): void
    {
        Log::info('progress');
        $this->importProgress = $payload['progress'];
        $this->importSuccessCount = $payload['successCount'];
    }

    #[On('echo:import.progress.{userId},ImportRowFailed')]
    public function handleRowFailed($payload): void
    {
        $this->importErrors[] = [
            'row' => $payload['rowNumber'],
            'message' => $payload['errorMessage'],
            'data' => $payload['rowData'],
        ];

    }
    #[On('echo:test,ImportFinished')]
    public function handleImportFinished($payload): void
    {
        Log::info('finished');
        $this->importCompleted = true;

    }
}
