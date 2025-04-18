<?php

declare(strict_types=1);

namespace App\Livewire\Commons;

use App\Enums\StatusImport as StudentImportEnum;
use App\Imports\StudentPreviewImport;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportZone extends Component
{
    use WithFileUploads;
    public const TextFileDefault = "Kéo & thả tệp vào đây hoặc click để chọn";


    public $file;
    public $fileName = "";
    public $importProgress = 0;
    public $importTotal = 0;
    public $importCompleted = false;
    public $importErrors = [];
    public $importSuccessCount = 0;
    public $importErrorCount = 0;
    public $userId;
    public $previewData = [];
    public $tempFile = null;

    public function mount(string $tempFile): void
    {
        $this->userId = Auth::id();
        $this->fileName = static::TextFileDefault;
        $this->tempFile = $tempFile;
    }

    public function render()
    {
        return view('livewire.commons.import-zone');
    }

    public function previewFile(): void
    {
        if (!$this->file) {
            return;
        }

        $import = new StudentPreviewImport();
        Excel::import($import, $this->file);

        $this->previewData = $import->data;

        $this->dispatch('onSetFileImport', previewData: $this->previewData);
    }


    public function updatedFile(): void
    {
        $this->fileName = $this->file ? $this->file->getClientOriginalName() : static::TextFileDefault;
        if ($this->file) {
            $this->previewFile();
        }
    }

    public function resetFile(): void
    {
        $this->file = null;
        $this->fileName = static::TextFileDefault;
        $this->previewData = [];
        $this->importTotal = 0;
        $this->importProgress = 0;
        $this->importCompleted = false;
        $this->importErrors = [];
        $this->importSuccessCount = 0;
        $this->importErrorCount = 0;
        $this->dispatch('onClearPreviewData');
    }

    public function import(): void
    {
        if (!$this->file) {
            return;
        }
        DB::beginTransaction();
        try {
            $path = $this->file->store(path: 'imports');
            DB::commit();
            $this->importCompleted = false;
            $this->importTotal = count($this->previewData);
            $this->dispatch('onImportFile', fileName: $this->file->getClientOriginalName(), filePath: $path);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    #[On('echo:import.progress.{userId},.import.started')]
    public function handleImportStarted($payload): void
    {
        Log::info('started');
        $this->importTotal = $payload['totalRecords'];
        $this->importProgress = 0;
        $this->importCompleted = false;
        $this->importErrors = [];
        $this->importSuccessCount = 0;
        $this->importErrorCount = 0;
    }

    #[On('echo:import.progress.{userId},.import.progress.updated')]
    public function handleProgressUpdated($payload): void
    {
        Log::info('progress');
        $this->importProgress = $payload['progress'];
        $this->importSuccessCount = $payload['successCount'];
        $this->importErrorCount = $payload['errorCount'];
    }

    #[On('echo:import.progress.{userId},.import.row.failed')]
    public function handleRowFailed($payload): void
    {
        Log::info('failed');
        $this->importErrors[] = [
            'row' => $payload['rowNumber'],
            'message' => $payload['errorMessage'],
            'data' => $payload['rowData'],
        ];
    }

    #[On('echo:import.progress.{userId},.import.finished')]
    public function handleImportFinished($payload): void
    {
        Log::info('finished');
        $this->importProgress = 100;
        $this->importCompleted = true;
        $this->importSuccessCount = $payload['successCount'];
        $this->importErrorCount = $payload['errorCount'];
        if (StudentImportEnum::Completed === $payload['status']) {
            $this->dispatch('alert', type: 'success', message: 'Import thành công');
        } elseif (StudentImportEnum::PartialyFaild === $payload['status']) {
            $this->dispatch('alert', type: 'warning', message: 'Import thành công một số bản ghi! Kiểm tra lại tệp!');
        } else {
            $this->dispatch('alert', type: 'error', message: 'Import thất bại! Kiểm tra lại tệp!');
        }
    }
    public function closeProcessModal(): void
    {
        $this->resetFile();
        $this->dispatch('onCloseProcessModal');
    }
}
