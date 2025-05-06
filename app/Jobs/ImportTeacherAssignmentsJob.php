<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport;
use App\Helpers\LogActivityHelper;
use App\Imports\TeacherAssignmentImport;
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

class ImportTeacherAssignmentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $userId;
    protected $importHistoryId;

    public function __construct($userId, $importHistoryId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
    }

    public function handle(): void
    {
        try {
            $importHistory = ImportHistory::find($this->importHistoryId);
            if (!$importHistory) {
                Log::error('Import history not found', ['id' => $this->importHistoryId]);
                return;
            }

            $importHistory->status = StatusImport::Processing;
            $importHistory->save();

            // Log the start of the import process
            LogActivityHelper::create(
                'Bắt đầu import phân công giáo viên',
                'Bắt đầu import phân công giáo viên từ file ' . $importHistory->file_name
            );

            $import = new TeacherAssignmentImport($this->userId, $this->importHistoryId);

            Excel::import($import, Storage::path($importHistory->path));

            // Log the completion of the import process
            LogActivityHelper::create(
                'Hoàn thành import phân công giáo viên',
                'Hoàn thành import phân công giáo viên từ file ' . $importHistory->file_name .
                ' với ' . $importHistory->successful_records . ' bản ghi thành công'
            );

            Storage::delete(Storage::path($importHistory->path));
        } catch (Throwable $e) {
            Log::error('Error in teacher assignment import job', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Log the error
            LogActivityHelper::create(
                'Lỗi import phân công giáo viên',
                'Lỗi khi import phân công giáo viên: ' . $e->getMessage()
            );
        }
    }
}
