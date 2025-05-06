<?php

declare(strict_types=1);

namespace App\Livewire\Class;

use App\Enums\StatusImport;
use App\Enums\TypeImport;
use App\Imports\SpecializedClassTransferPreviewImport;
use App\Jobs\ImportSpecializedClassTransferJob;
use App\Models\ImportHistory;
use App\Services\SsoService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ImportSpecializedTransfer extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $file;
    public array $previewData = [];
    public bool $showPreview = false;

    public function render()
    {
        return view('livewire.class.import-specialized-transfer');
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $this->previewData = [];
        $this->showPreview = false;

        try {
            $import = new SpecializedClassTransferPreviewImport();
            $this->previewData = Excel::toArray($import, $this->file)[0];

            // Remove header row
            array_shift($this->previewData);

            // Format preview data
            $this->previewData = array_map(fn ($row, $index) => [
                'stt' => $index + 1,
                'ma_sv' => $row[0] ?? '',
                'ho_ten' => $row[1] ?? '',
                'lop_hien_tai' => $row[2] ?? '',
                'lop_chuyen_nganh' => $row[3] ?? '',
                'nam_hoc' => $row[4] ?? '',
            ], $this->previewData, array_keys($this->previewData));

            $this->showPreview = true;
        } catch (Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi đọc file: ' . $e->getMessage());
        }
    }

    #[On('onImportFile')]
    public function import($fileName, $filePath): void
    {
        try {
            $facultyId = app(SsoService::class)->getFacultyId();
            $importHistory = ImportHistory::create([
                'file_name' => $fileName,
                'path' => $filePath,
                'status' => StatusImport::Pending,
                'total_records' => count($this->previewData),
                'successful_records' => 0,
                'faculty_id' => $facultyId,
                'type' => TypeImport::SpecializedClassTransfer,
                'created_by' => Auth::id(),
                'admission_year_id' => 0 // Not needed for specialized class transfer
            ]);

            // System log only for debugging
            Log::info('Created specialized class transfer import', [
                'file' => $fileName,
                'records' => count($this->previewData)
            ]);

            dispatch(new ImportSpecializedClassTransferJob(Auth::id(), $importHistory->id));
            $this->dispatch('onOpenProcessModal');
        } catch (Exception $e) {
            Log::error('Error creating specialized class transfer import', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Có lỗi xảy ra khi tạo import: ' . $e->getMessage());
        }
    }

    public function startImport(): void
    {
        if (!$this->file) {
            session()->flash('error', 'Vui lòng chọn file để import.');
            return;
        }

        try {
            $fileName = $this->file->getClientOriginalName();
            $path = $this->file->store('imports');

            // System log only for debugging
            Log::info('Uploaded specialized class transfer file', [
                'file' => $fileName
            ]);

            $this->dispatch('onImportFile', fileName: $fileName, filePath: $path);
        } catch (Exception $e) {
            Log::error('Error storing specialized class transfer file', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Có lỗi xảy ra khi lưu file: ' . $e->getMessage());
        }
    }
}
