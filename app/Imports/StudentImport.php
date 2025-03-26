<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\StatusImport as StudentImportEnum;
use App\Events\ImportFinished;
use App\Events\ImportProgressUpdated;
use App\Events\ImportRowFailed;
use App\Events\ImportStarted;
use App\Helpers\Helper;
use App\Models\ImportError;
use App\Models\ImportHistory;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class StudentImport implements ToModel, WithChunkReading, WithStartRow, WithEvents
{
    private $userId;
    private $importHistoryId;
    private $startTime;
    private $successCount = 0;
    private $errorCount = 0;
    private $processed = 0;
    private $totalRows = 0;

    public function __construct($userId, $importHistoryId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event): void {
                $this->startTime = Carbon::now();
                $history = ImportHistory::find($this->importHistoryId);
                $this->totalRows = $history->total_records;

                broadcast(new ImportStarted(
                    $this->userId,
                    $this->totalRows,
                    $this->importHistoryId
                ));
            },

            AfterImport::class => function (AfterImport $event): void {
                $timeElapsed = Carbon::now()->diffInSeconds($this->startTime);

                broadcast(new ImportFinished(
                    $this->userId,
                    $this->importHistoryId,
                    $this->errorCount > 0 ? StudentImportEnum::PartialyFaild : StudentImportEnum::Completed,
                    $this->successCount,
                    $this->errorCount,
                    $this->getErrors(),
                    gmdate('H:i:s', (int)$timeElapsed)
                ));
            },

            ImportFailed::class => function (ImportFailed $event): void {
                broadcast(new ImportFinished(
                    $this->userId,
                    $this->importHistoryId,
                    StudentImportEnum::Failed,
                    $this->successCount,
                    $this->errorCount,
                    $this->getErrors(),
                    'N/A'
                ));
            },
        ];
    }

    public function model(array $row): void
    {
        $this->processed++;

        try {
            // Logic tạo student
            // [$lastName, $firtName] = Helper::splitFullName($row[3]);
            // $student = new Student([
            //     'code_import'     => $row[1] ?? '',
            //     'code'           => $row[2] ?? '',
            //     'first_name'          => $firtName,
            //     'last_name' => $lastName,
            //     'dob'       => $row[4] ?? '',
            //     'gioi_tinh'       => $row[5] ?? '',
            //     'lop'            => $row[6] ?? '',
            //     'khoa'            => $row[7] ?? '',
            //     'nien_khoa'       => $row[8] ?? '',
            //     'dan_toc'        => $row[9] ?? '',
            //     'dien_thoai'      => $row[10] ?? '',
            //     'email'          => $row[11] ?? '',
            //     'dia_chi_bao_tin' => $row[12] ?? '',
            //     'ho_ten_bo'       => $row[13] ?? '',
            //     'sdt_bo'          => $row[14] ?? '',
            //     'ho_ten_me'      => $row[15] ?? '',
            //     'sdt_me'          => $row[16] ?? '',
            // ]);
            //
            // $student->save();
            $this->successCount++;

            // Gửi cập nhật tiến trình mỗi 100 bản ghi
            if (0 === $this->processed % 100) {
                if (0 === $this->totalRows) {
                    $progress = 0;
                } else {
                    $progress = ($this->processed / $this->totalRows) * 100;
                }

                event(new ImportProgressUpdated(
                    $this->userId,
                    $this->importHistoryId,
                    round($progress, 2),
                    $this->processed,
                    $this->successCount,
                    $this->errorCount,
                    $row
                ));
            }


        } catch (Exception $e) {
            $this->errorCount++;

            // Lưu lỗi vào database
            ImportError::create([
                'import_history_id' => $this->importHistoryId,
                'row_number' => $this->processed + 1,
                'error_message' => $e->getMessage(),
                'record_data' => json_encode($row),
            ]);

            // Gửi event thông báo lỗi
            event(new ImportRowFailed(
                $this->userId,
                $this->importHistoryId,
                $this->processed + 1,
                $e->getMessage(),
                $row
            ));

        }
    }
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return ImportError::where('import_history_id', $this->importHistoryId)
            ->get()
            ->toArray();
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function startRow(): int
    {
        return 2;
    }
}
