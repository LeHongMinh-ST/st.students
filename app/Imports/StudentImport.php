<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\FamilyRelationship;
use App\Enums\Gender;
use App\Enums\Status;
use App\Enums\StatusImport as StudentImportEnum;
use App\Events\ImportFinished;
use App\Events\ImportProgressUpdated;
use App\Events\ImportStarted;
use App\Helpers\DateTimeHelper;
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
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class StudentImport implements ToArray, WithChunkReading, WithEvents, WithStartRow
{
    protected $userId;

    protected $importHistoryId;

    protected $admissionYearId;

    protected $history;

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
            BeforeImport::class => function (): void {
                $this->startTime = Carbon::now();
                $this->totalRows = $this->history->total_records;

                broadcast(new ImportStarted(
                    $this->userId,
                    $this->totalRows,
                    $this->importHistoryId
                ));
            },

            AfterImport::class => function (): void {
                $timeElapsed = Carbon::now()->diffInSeconds($this->startTime);

                broadcast(new ImportFinished(
                    $this->userId,
                    $this->importHistoryId,
                    $this->errorCount > 0 ? StudentImportEnum::PartialyFaild : StudentImportEnum::Completed,
                    $this->successCount,
                    $this->errorCount,
                    gmdate('H:i:s', (int) $timeElapsed)
                ));

                ImportHistory::where('id', $this->importHistoryId)
                    ->update([
                        'successful_records' => $this->successCount,
                        'status' => $this->errorCount > 0 ? StudentImportEnum::PartialyFaild : StudentImportEnum::Completed,
                    ]);

            },

            ImportFailed::class => function (): void {
                broadcast(new ImportFinished(
                    $this->userId,
                    $this->importHistoryId,
                    StudentImportEnum::Failed,
                    $this->successCount,
                    $this->errorCount,
                    'N/A'
                ));

                ImportHistory::where('id', $this->importHistoryId)
                    ->update([
                        'successful_records' => $this->successCount,
                        'status' => StudentImportEnum::Failed,
                    ]);

            },
        ];
    }

    public function array(array $array): void
    {
        foreach ($array as $row) {
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

            }

            // Update progress every 50 rows
            if (0 === $this->processed % 50) {
                $progress = ($this->totalRows > 0) ? ($this->processed / $this->totalRows) * 100 : 0;

                // Dispatch an event to notify about import progress
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
        }
    }

    /**
     * Get the number of successfully imported rows.
     *
     * @return int The count of successfully imported rows.
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /**
     * Get the number of rows that failed to import.
     *
     * @return int The count of failed import rows.
     */
    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    /**
     * Define the chunk size for batch processing.
     *
     * @return int The number of rows processed per chunk.
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Define the starting row for the import process.
     *
     * @return int The row number where data starts (skip headers).
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Process and import a single row of student data.
     *
     * @param  array  $row  The row data from the import file.
     *
     * @throws Exception If any processing error occurs.
     */
    protected function handleImport($row): void
    {
        $class = $this->getClass($row[6] ?? '');

        // Create or update student
        $student = $this->createOrUpdateStudent($row);

        // Associate student with class
        $this->syncStudentWithClass($student, $class, $row);

        // Sync family information
        $this->syncFamily($student, $row);
    }

    /**
     * Create a new class record if it does not exist.
     *
     * @param  string  $classCode  The class code to create.
     * @return ClassGenerate The newly created class instance.
     */
    private function createClass(string $classCode): ClassGenerate
    {
        $class = new ClassGenerate();
        $class->code = $classCode;
        $class->name = $classCode;
        $class->description = $classCode;
        $class->admission_year_id = $this->admissionYearId;
        $class->faculty_id = $this->history->faculty_id;
        $class->save();

        return $class;
    }

    /**
     * Retrieve an existing class or create a new one if it does not exist.
     *
     * @param  string  $classCode  The class code to search for.
     * @return ClassGenerate The class instance found or created.
     */
    private function getClass(string $classCode): ClassGenerate
    {
        $class = ClassGenerate::where('code', $classCode)->first();
        if (! $class) {
            $class = $this->createClass($classCode);
        }

        return $class;
    }

    /**
     * Create or update student based on their unique code.
     *
     * @param  array  $row
     */
    private function createOrUpdateStudent($row): Student
    {
        $name = Helper::splitFullName($row[3]);
        [$schoolYearStart, $schoolYearEnd] = SchoolHelper::extractYears($row[8]);

        $data = [
            'code_import' => $row[1] ?? '',
            'first_name' => $name['first_name'] ?? '',
            'last_name' => $name['last_name'] ?? '',
            'dob' => $row[4] ? DateTimeHelper::createDateTime($row[4]) : null,
            'gender' => $row[5] ? Gender::mapValue($row[5]) : '',
            'school_year_start' => $schoolYearStart ?? '',
            'school_year_end' => $schoolYearEnd ?? '',
            'ethnic' => $row[9] ?? '',
            'phone' => $row[10] ?? '',
            'email' => $row[11] ?? '',
            'email_edu' => "{$row[2]}@sv.vnua.edu.vn",
            'address' => $row[12] ?? '',
            'admission_year_id' => $this->admissionYearId,
            'faculty_id' => $this->history->faculty_id,
            'code' => $row[2],
        ];

        $student = Student::updateOrCreate(['code' => $row[2]], $data);

        // Delete old family records
        $student->families()->delete();

        return $student;
    }

    /**
     * Associate student with their class.
     */
    private function syncStudentWithClass(Student $student, ClassGenerate $class, array $row): void
    {
        [$schoolYearStart] = SchoolHelper::extractYears($row[8]);

        $class->students()->syncWithoutDetaching([
            $student->id => [
                'status' => Status::Active->value,
                'start_year' => $schoolYearStart,
            ],
        ]);
    }

    /**
     * Sync student family information.
     */
    private function syncFamily(Student $student, array $row): void
    {
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
}
