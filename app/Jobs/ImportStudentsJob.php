<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport as StatusImportEnum;
use App\Imports\StudentImport;
use App\Models\ImportHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $importHistory = ImportHistory::find($this->importHistoryId);
        $importHistory->status = StatusImportEnum::Processing;
        $importHistory->save();

        $import = new StudentImport($this->userId, $this->importHistoryId, $this->admissionYearId);

        Excel::queueImport($import, Storage::path($importHistory->path), );


    }

}
