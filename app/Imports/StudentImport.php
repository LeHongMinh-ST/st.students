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
                    $this->getErrors(),
                    gmdate('H:i:s', (int)$timeElapsed)
                ));
            },

            ImportFailed::class => function (): void {
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
        // Increment the processed row count
        $this->processed++;

        // Begin a database transaction to ensure data consistency
        DB::beginTransaction();
        try {
            // Process the current row and import data
            $this->handleImport($row);
            $this->successCount++;

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

            // Dispatch an event to notify about the failed row import
            event(new ImportRowFailed(
                $this->userId,
                $this->importHistoryId,
                $this->processed + 1,
                $e->getMessage(),
                $row
            ));
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
     * Retrieve all error records related to the current import history.
     *
     * @return array List of import errors.
     */
    public function getErrors(): array
    {
        return ImportError::where('import_history_id', $this->importHistoryId)
            ->get()
            ->toArray();
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
     * @param array $row The row data from the import file.
     * @throws Exception If any processing error occurs.
     */
    protected function handleImport(array $row): void
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
     * @param string $classCode The class code to create.
     * @return ClassGenerate The newly created class instance.
     */
    private function createClass(string $classCode): ClassGenerate
    {
        $class = new ClassGenerate();
        $class->code = $classCode;
        $class->name = $classCode;
        $class->admission_year_id = $this->admissionYearId;
        $class->faculty_id = $this->history->faculty_id;
        $class->save();

        return $class;
    }

    /**
     * Retrieve an existing class or create a new one if it does not exist.
     *
     * @param string $classCode The class code to search for.
     * @return ClassGenerate The class instance found or created.
     */
    private function getClass(string $classCode): ClassGenerate
    {
        $class = ClassGenerate::where('code', $classCode)->first();
        if (!$class) {
            $class = $this->createClass($classCode);
        }
        return $class;
    }

    /**
     * Create or update student based on their unique code.
     *
     * @param array $row
     * @return Student
     */
    private function createOrUpdateStudent(array $row): Student
    {
        [$lastName, $firstName] = Helper::splitFullName($row[3]);
        [$schoolYearStart, $schoolYearEnd] = SchoolHelper::extractYears($row[8]);

        $data = [
            'code_import' => $row[1] ?? '',
            'first_name' => $firstName,
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
        ];

        $student = Student::updateOrCreate(['code' => $row[2]], $data);

        // Delete old family records
        $student->families()->delete();

        return $student;
    }

    /**
     * Associate student with their class.
     *
     * @param Student $student
     * @param ClassGenerate $class
     * @param array $row
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
     *
     * @param Student $student
     * @param array $row
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
