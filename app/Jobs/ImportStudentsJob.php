<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport as StatusImportEnum;
use App\Helpers\LogActivityHelper;
use App\Imports\StudentImport;
use App\Models\ImportHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportStudentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $userId;
    protected $importHistoryId;
    protected $admissionYearId;

    public function __construct($userId, $importHistoryId, $admissionYearId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
        $this->admissionYearId = $admissionYearId;
    }

    public function handle(): void
    {
        try {
            $importHistory = ImportHistory::find($this->importHistoryId);
            if (!$importHistory) {
                Log::error('Import history not found', ['id' => $this->importHistoryId]);
                return;
            }

            $importHistory->status = StatusImportEnum::Processing;
            $importHistory->save();

            // Log the start of the import process
            LogActivityHelper::create(
                'Bắt đầu import sinh viên',
                'Bắt đầu import sinh viên từ file ' . $importHistory->file_name
            );

            $import = new StudentImport($this->userId, $this->importHistoryId, $this->admissionYearId);

            Excel::import($import, Storage::path($importHistory->path));

            // Log the completion of the import process
            LogActivityHelper::create(
                'Hoàn thành import sinh viên',
                'Hoàn thành import sinh viên từ file ' . $importHistory->file_name .
                ' với ' . $importHistory->successful_records . ' bản ghi thành công'
            );

            Storage::delete(Storage::path($importHistory->path));
        } catch (Throwable $e) {
            Log::error('Error in student import job', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Log the error
            LogActivityHelper::create(
                'Lỗi import sinh viên',
                'Lỗi khi import sinh viên: ' . $e->getMessage()
            );
        }
    }

}
