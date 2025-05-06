<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\Status;
use App\Models\ClassAssign;
use App\Models\ClassGenerate;
use App\Models\ImportError;
use App\Models\ImportHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class TeacherAssignmentImport implements ToArray, WithChunkReading, WithEvents, WithHeadingRow
{
    protected $userId;
    protected $importHistoryId;
    protected $history;
    private $startTime;
    private $successCount = 0;
    private $errorCount = 0;
    private $processed = 0;
    private $totalRows = 0;

    public function __construct($userId, $importHistoryId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
        $this->history = ImportHistory::find($this->importHistoryId);
        $this->startTime = now();
    }

    public function array(array $rows): void
    {
        foreach ($rows as $row) {
            // Increment the processed row count
            $this->processed++;

            // Begin a database transaction to ensure data consistency
            DB::beginTransaction();
            try {
                // Process the current row and import data
                $this->handleImport($row);
                $this->successCount++;

                // Commit the transaction if no errors occurred
                DB::commit();
            } catch (Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
                $this->errorCount++;

                // Save error details to the ImportError table
                ImportError::create([
                    'import_history_id' => $this->importHistoryId,
                    'row_number' => $this->processed + 1,
                    'error_message' => $e->getMessage(),
                    'record_data' => json_encode($row),
                ]);

                Log::error('Import error: ' . $e->getMessage(), [
                    'row' => $row,
                    'import_history_id' => $this->importHistoryId,
                ]);
            }

            // Update progress every 10 rows or at the end
            if (0 === $this->processed % 10 || $this->processed === $this->totalRows) {
                $this->updateProgress();
            }
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event): void {
                $this->totalRows = $event->getReader()->getTotalRows()['Worksheet'] ?? 0;
                $this->history->update([
                    'total_records' => $this->totalRows,
                    'status' => \App\Enums\StatusImport::Processing,
                ]);

                // Broadcast the import started event
                broadcast(new \App\Events\ImportStarted(
                    $this->userId,
                    $this->totalRows
                ));
            },
            AfterImport::class => function (AfterImport $event): void {
                $status = $this->determineStatus();
                $this->history->update([
                    'status' => $status,
                    'successful_records' => $this->successCount,
                    'failed_records' => $this->errorCount,
                    'completed_at' => now(),
                ]);

                // Broadcast the import finished event
                broadcast(new \App\Events\ImportFinished(
                    $this->userId,
                    $this->successCount,
                    $this->errorCount,
                    $status
                ));
            },
            ImportFailed::class => function (ImportFailed $event): void {
                $this->history->update([
                    'status' => \App\Enums\StatusImport::Failed,
                    'successful_records' => $this->successCount,
                    'failed_records' => $this->errorCount,
                    'completed_at' => now(),
                ]);

                // Broadcast the import finished event with failed status
                broadcast(new \App\Events\ImportFinished(
                    $this->userId,
                    $this->successCount,
                    $this->errorCount,
                    \App\Enums\StatusImport::Failed
                ));
            },
        ];
    }

    protected function handleImport($row): void
    {
        // Validate required fields
        if (empty($row['ma_lop']) || empty($row['ma_giang_vien']) || empty($row['nam_hoc'])) {
            throw new Exception('Thiếu thông tin bắt buộc: Mã lớp, Mã giảng viên hoặc Năm học');
        }

        // Find the class by code
        $class = ClassGenerate::where('code', $row['ma_lop'])->first();
        if (!$class) {
            throw new Exception('Không tìm thấy lớp học với mã: ' . $row['ma_lop']);
        }

        // Find the teacher by code
        $teacher = User::where('code', $row['ma_giang_vien'])->first();
        if (!$teacher) {
            throw new Exception('Không tìm thấy giảng viên với mã: ' . $row['ma_giang_vien']);
        }

        // Check if the teacher has the required permission
        if (!$teacher->hasPermission('class.teacher')) {
            throw new Exception('Giảng viên không có quyền làm giáo viên chủ nhiệm: ' . $teacher->full_name);
        }

        // Check if there's already an assignment for this class and year
        $existingAssignment = ClassAssign::where('class_id', $class->id)
            ->where('year', $row['nam_hoc'])
            ->first();

        if ($existingAssignment) {
            // Update existing assignment
            $existingAssignment->update([
                'teacher_id' => $teacher->id,
                'status' => Status::Active->value,
            ]);
        } else {
            // Create new assignment
            ClassAssign::create([
                'class_id' => $class->id,
                'teacher_id' => $teacher->id,
                'year' => $row['nam_hoc'],
                'status' => Status::Active->value,
            ]);
        }
    }

    private function updateProgress(): void
    {
        $progress = $this->totalRows > 0 ? round(($this->processed / $this->totalRows) * 100) : 0;

        // Broadcast the progress update event
        broadcast(new \App\Events\ImportProgressUpdated(
            $this->userId,
            $progress,
            $this->successCount,
            $this->errorCount
        ));
    }

    private function determineStatus(): string
    {
        if (0 === $this->errorCount && $this->successCount > 0) {
            return \App\Enums\StatusImport::Completed;
        }
        if ($this->errorCount > 0 && $this->successCount > 0) {
            return \App\Enums\StatusImport::PartialyFaild;
        }
        return \App\Enums\StatusImport::Failed;

    }
}
