<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport;
use App\Helpers\LogActivityHelper;
use App\Imports\SpecializedClassTransferImport;
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

class ImportSpecializedClassTransferJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $userId;
    protected $importHistoryId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $importHistoryId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
    }

    /**
     * Execute the job.
     */
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

            // Only log system information, not user activity
            Log::info('Starting specialized class transfer import', [
                'file' => $importHistory->file_name,
                'records' => $importHistory->total_records
            ]);

            $import = new SpecializedClassTransferImport($this->userId, $this->importHistoryId);

            Excel::import($import, Storage::path($importHistory->path));

            // Log the completion of the import process
            LogActivityHelper::create(
                'Import phân lớp chuyên ngành',
                'Import phân lớp chuyên ngành từ file ' . $importHistory->file_name .
                ' với ' . $importHistory->successful_records . ' bản ghi thành công'
            );

            Storage::delete(Storage::path($importHistory->path));
        } catch (Throwable $e) {
            Log::error('Error in specialized class transfer import job', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
