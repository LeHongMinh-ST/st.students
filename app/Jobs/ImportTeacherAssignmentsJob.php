<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport;
use App\Imports\TeacherAssignmentImport;
use App\Models\ImportHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $importHistory = ImportHistory::find($this->importHistoryId);
        $importHistory->status = StatusImport::Processing;
        $importHistory->save();

        $import = new TeacherAssignmentImport($this->userId, $this->importHistoryId);

        Excel::import($import, Storage::path($importHistory->path));

        Storage::delete(Storage::path($importHistory->path));
    }
}
