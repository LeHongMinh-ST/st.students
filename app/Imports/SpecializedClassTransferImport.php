<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Enums\StatusImport as ImportStatus;
use App\Events\ImportFinished;
use App\Events\ImportProgressUpdated;
use App\Events\ImportStarted;
use App\Helpers\LogActivityHelper;
use App\Models\ClassGenerate;
use App\Models\ImportError;
use App\Models\ImportHistory;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class SpecializedClassTransferImport implements ToArray, WithChunkReading, WithEvents, WithStartRow
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
    }

    public function array(array $rows): void
    {
        foreach ($rows as $row) {
            try {
                DB::beginTransaction();
                $this->handleImport($row);
                $this->successCount++;
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $this->errorCount++;
                ImportError::create([
                    'import_history_id' => $this->importHistoryId,
                    'row_data' => json_encode($row),
                    'error_message' => $e->getMessage(),
                ]);
            }

            $this->processed++;
            $this->updateProgress();
        }
    }

    public function startRow(): int
    {
        return 2; // Skip header row
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event): void {
                $this->startTime = now();
                $this->totalRows = $event->getReader()->getTotalRows()['Worksheet'] ?? 0;

                // Update import history status to processing
                $this->history->update([
                    'status' => ImportStatus::Processing,
                    'total_records' => $this->totalRows,
                ]);

                event(new ImportStarted($this->importHistoryId, $this->totalRows));
            },
            AfterImport::class => function (AfterImport $event): void {
                $duration = now()->diffInSeconds($this->startTime);

                // Update import history with results
                $this->history->update([
                    'status' => ImportStatus::Completed,
                    'successful_records' => $this->successCount,
                    'failed_records' => $this->errorCount,
                    'duration' => $duration,
                ]);

                event(new ImportFinished($this->importHistoryId, $this->successCount, $this->errorCount, $duration));
            },
            ImportFailed::class => function (ImportFailed $event): void {
                $this->history->update([
                    'status' => ImportStatus::Failed,
                    'failed_records' => $this->totalRows,
                ]);

                ImportError::create([
                    'import_history_id' => $this->importHistoryId,
                    'row_data' => 'Import process failed',
                    'error_message' => $event->getException()->getMessage(),
                ]);

                event(new ImportFinished($this->importHistoryId, $this->successCount, $this->errorCount, 0));
            },
        ];
    }

    /**
     * Process and import a single row of specialized class transfer data.
     *
     * @param  array  $row  The row data from the import file.
     *
     * @throws Exception If any processing error occurs.
     */
    protected function handleImport($row): void
    {
        // Get student by code
        $student = $this->getStudent($row[0] ?? '');

        // Get target specialized class
        $specializedClass = $this->getSpecializedClass($row[3] ?? '');

        // Get academic year
        $academicYear = $row[4] ?? '';

        // Validate academic year format (e.g., 2023-2024)
        if (!preg_match('/^\d{4}-\d{4}$/', $academicYear)) {
            throw new Exception("Định dạng năm học không hợp lệ: {$academicYear}. Định dạng đúng là YYYY-YYYY.");
        }

        // Get current basic class
        $currentClass = $this->getCurrentBasicClass($student);

        // Transfer student to specialized class
        $this->transferStudentToSpecializedClass($student, $currentClass, $specializedClass, $academicYear);
    }

    /**
     * Get student by code.
     *
     * @param  string  $studentCode  The student code to search for.
     * @return Student The student instance.
     * @throws Exception If student not found.
     */
    private function getStudent(string $studentCode): Student
    {
        $student = Student::where('code', $studentCode)->first();
        if (!$student) {
            throw new Exception("Không tìm thấy sinh viên với mã: {$studentCode}");
        }

        return $student;
    }

    /**
     * Get specialized class by code.
     *
     * @param  string  $classCode  The class code to search for.
     * @return ClassGenerate The class instance.
     * @throws Exception If class not found or not a specialized class.
     */
    private function getSpecializedClass(string $classCode): ClassGenerate
    {
        $class = ClassGenerate::where('code', $classCode)->first();
        if (!$class) {
            throw new Exception("Không tìm thấy lớp với mã: {$classCode}");
        }

        if (ClassType::Major !== $class->type) {
            throw new Exception("Lớp {$classCode} không phải là lớp chuyên ngành");
        }

        return $class;
    }

    /**
     * Get current basic class of a student.
     *
     * @param  Student  $student  The student.
     * @return ClassGenerate|null The current basic class.
     */
    private function getCurrentBasicClass(Student $student): ?ClassGenerate
    {
        $currentClass = $student->classes()
            ->wherePivot('status', Status::Active->value)
            ->whereHas('pivot', function ($query): void {
                $query->whereNull('end_year');
            })
            ->where('type', ClassType::Basic)
            ->first();

        if (!$currentClass) {
            throw new Exception("Sinh viên {$student->code} không thuộc lớp cơ bản nào");
        }

        return $currentClass;
    }

    /**
     * Transfer student from basic class to specialized class.
     *
     * @param  Student  $student  The student to transfer.
     * @param  ClassGenerate  $currentClass  The current basic class.
     * @param  ClassGenerate  $specializedClass  The target specialized class.
     * @param  string  $academicYear  The academic year for the transfer.
     */
    private function transferStudentToSpecializedClass(
        Student $student,
        ClassGenerate $currentClass,
        ClassGenerate $specializedClass,
        string $academicYear
    ): void {
        // Extract the start year from the academic year (e.g., 2023 from 2023-2024)
        $startYear = explode('-', $academicYear)[0];

        // Set end year for current basic class
        $student->classes()->updateExistingPivot($currentClass->id, [
            'end_year' => $startYear,
        ]);

        // System log only for debugging
        Log::debug('Student basic class ended', [
            'student_id' => $student->id,
            'student_code' => $student->code,
            'class_id' => $currentClass->id,
            'class_code' => $currentClass->code,
            'end_year' => $startYear
        ]);

        // Check if student is already in the specialized class
        $existingSpecializedClass = $student->classes()
            ->where('id', $specializedClass->id)
            ->first();

        if ($existingSpecializedClass) {
            // Update the existing relationship
            $student->classes()->updateExistingPivot($specializedClass->id, [
                'status' => Status::Active->value,
                'start_year' => $startYear,
                'end_year' => null,
            ]);

            // System log only for debugging
            Log::debug('Student specialized class updated', [
                'student_id' => $student->id,
                'student_code' => $student->code,
                'class_id' => $specializedClass->id,
                'class_code' => $specializedClass->code,
                'start_year' => $startYear
            ]);
        } else {
            // Add student to the specialized class
            $student->classes()->attach($specializedClass->id, [
                'status' => Status::Active->value,
                'start_year' => $startYear,
            ]);

            // System log only for debugging
            Log::debug('Student added to specialized class', [
                'student_id' => $student->id,
                'student_code' => $student->code,
                'class_id' => $specializedClass->id,
                'class_code' => $specializedClass->code,
                'start_year' => $startYear
            ]);
        }

        // Log the activity for user tracking
        LogActivityHelper::create(
            'Chuyển lớp chuyên ngành',
            'Chuyển sinh viên ' . $student->full_name . ' (Mã SV: ' . $student->code . ') ' .
            'từ lớp ' . $currentClass->name . ' sang lớp chuyên ngành ' . $specializedClass->name .
            ' cho năm học ' . $academicYear
        );
    }

    /**
     * Update import progress.
     */
    private function updateProgress(): void
    {
        if ($this->totalRows > 0) {
            $progress = round(($this->processed / $this->totalRows) * 100);

            // Update import history progress
            $this->history->update([
                'progress' => $progress,
                'successful_records' => $this->successCount,
                'failed_records' => $this->errorCount,
            ]);

            // Broadcast progress update event
            event(new ImportProgressUpdated(
                $this->importHistoryId,
                $progress,
                $this->successCount,
                $this->errorCount,
                $this->processed,
                $this->totalRows
            ));
        }
    }
}
