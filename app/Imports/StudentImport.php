<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\FamilyRelationship;
use App\Enums\Gender;
use App\Enums\Status;
use App\Enums\StatusImport as StudentImportEnum;
use App\Events\ImportFinished;
use App\Events\ImportProgressUpdated;
use App\Events\ImportRowFailed;
use App\Events\ImportStarted;
use App\Helpers\Helper;
use App\Helpers\SchoolHelper;
use App\Models\ClassGenerate;
use App\Models\Family;
use App\Models\ImportError;
use App\Models\ImportHistory;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
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
    private $admissionYearId;
    private $history;
    private $startTime;
    private $successCount = 0;
    private $errorCount = 0;
    private $processed = 0;
    private $totalRows = 0;

    public function __construct($userId, $importHistoryId, $admissionYearId)
    {
        $this->userId = $userId;
        $this->importHistoryId = $importHistoryId;
        $this->admissionYearId = $admissionYearId;
        $this->history = ImportHistory::find($this->importHistoryId);
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event): void {
                $this->startTime = Carbon::now();
                $this->totalRows = $this->history->total_records;

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

        DB::beginTransaction();
        try {
            $this->handleImport($row);
            $this->successCount++;

            if (0 === $this->processed % 50) {
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
            DB::commit();


        } catch (Exception $e) {
            DB::rollBack();
            $this->errorCount++;

            ImportError::create([
                'import_history_id' => $this->importHistoryId,
                'row_number' => $this->processed + 1,
                'error_message' => $e->getMessage(),
                'record_data' => json_encode($row),
            ]);

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

    protected function handleImport($row): void
    {

        $classCode = $row[6] ?? '';
        $class = $this->getClass($classCode);

        [$lastName, $firtName] = Helper::splitFullName($row[3]);
        [$schoolYearStart, $schoolYearEnd] = SchoolHelper::extractYears($row[8]);
        $student = Student::create([
            'code_import' => $row[1] ?? '',
            'code' => $row[2] ?? '',
            'first_name' => $firtName,
            'last_name' => $lastName,
            'dob' => $row[4] ?? '',
            'gender' => $row[5] ? Gender::mapValue($row[5]) : '',
            'faculty_id' => $this->history->faculty_id,
            'school_year_start' => $schoolYearStart ?? '',
            'school_year_end' => $schoolYearEnd ?? '',
            'ethnic' => $row[9] ?? '',
            'phone' => $row[10] ?? '',
            'email' => $row[11] ?? '',
            'email_edu' => "{$row[2]}@sv.vnua.edu.vn",
            'address' => $row[12] ?? '',
        ]);

        // Sync student with class
        $class->students()->syncWithoutDetaching([
            $student->id => [
                'status' => Status::Active->value,
                'start_year' => $schoolYearStart,
            ],
        ]);

        Family::create([
            'student_id' => $student->id,
            'relationship' => FamilyRelationship::Father,
            'full_name' => $row[13] ?? '',
            'phone' => $row[14] ?? '',
        ]);

        Family::create([
            'student_id' => $student->id,
            'relationship' => FamilyRelationship::Mother,
            'full_name' => $row[15] ?? '',
            'phone' => $row[16] ?? '',
        ]);
    }

    private function createClass($classCode): ClassGenerate
    {
        $class = new ClassGenerate();
        $class->code = $classCode;
        $class->name = $classCode;
        $class->admission_year_id = $this->admissionYearId;
        $class->faculty_id = $this->history->faculty_id;
        $class->save();

        return $class;
    }
    private function getClass($classCode): ClassGenerate
    {
        $class = ClassGenerate::where('code', $classCode)->first();
        if (!$class) {
            $class = $this->createClass($classCode);
        }
        return $class;
    }
}
