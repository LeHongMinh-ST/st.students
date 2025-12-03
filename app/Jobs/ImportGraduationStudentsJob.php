<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\RankGraduate;
use App\Enums\StatusImport;
use App\Enums\StudentStatus;
use App\Imports\GraduationStudentPreviewImport;
use App\Models\GraduationCeremony;
use App\Models\ImportHistory;
use App\Models\Student;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportGraduationStudentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $userId,
        private readonly int $importHistoryId,
        private readonly int $ceremonyId
    ) {
    }

    public function handle(): void
    {
        $importHistory = ImportHistory::find($this->importHistoryId);
        if (!$importHistory) {
            Log::error('Import history not found', ['id' => $this->importHistoryId]);
            return;
        }

        $ceremony = GraduationCeremony::find($this->ceremonyId);
        if (!$ceremony) {
            Log::error('Graduation ceremony not found', ['id' => $this->ceremonyId]);
            $importHistory->update(['status' => StatusImport::Failed]);
            return;
        }
        Log::info('Starting import for ceremony', ['ceremony_id' => $ceremony->id]);

        try {
            $importHistory->update(['status' => StatusImport::Processing]);

            $filePath = Storage::path($importHistory->path);
            $rows = Excel::toArray(new GraduationStudentPreviewImport(), $filePath)[0];

            // Remove header row
            array_shift($rows);

            $successCount = 0;
            $errors = [];

            DB::beginTransaction();
            Log::info('Total rows: ' . count($rows));

            foreach ($rows as $index => $row) {
                try {
                    $studentCode = $row[0] ?? null;
                    $fullName = $row[1] ?? null;
                    $email = $row[2] ?? null;
                    $gpa = $row[3] ?? null;
                    $rankValue = $row[4] ?? null;
                    $industryCode = $row[5] ?? null;
                    $industryName = $row[6] ?? null;
                    $citizen_identification = $row[7] ?? null;
                    $phone_number = $row[8] ?? null;
                    Log::info('Importing row ' . ($index + 2) . ': ' . json_encode($row));

                    if (!$studentCode || !$fullName) {
                        $errors[] = "Dòng " . ($index + 2) . ": Thiếu mã sinh viên hoặc họ tên";
                        continue;
                    }

                    // Find student by code
                    $student = Student::where('code', $studentCode)->first();
                    Log::info('Found student: ' . $student);

                    if (!$student) {
                        Log::warning('Student not found', ['code' => $studentCode]);
                        $errors[] = "Dòng " . ($index + 2) . ": Không tìm thấy sinh viên với mã " . $studentCode;
                        continue;
                    }

                    // Convert GPA to float
                    $gpaFloat = (float) str_replace(',', '.', (string) $gpa);

                    // Determine rank if not provided
                    if (!$rankValue) {
                        $rank = RankGraduate::fromGpa($gpaFloat);
                    } else {
                        // Try to match the provided rank with an enum value
                        $rank = match (mb_strtolower(mb_trim($rankValue))) {
                            'xuất sắc', 'excellent' => RankGraduate::Excellent,
                            'giỏi', 'very good' => RankGraduate::VeryGood,
                            'khá', 'good' => RankGraduate::Good,
                            default => RankGraduate::Average,
                        };
                    }

                    // Update student status to graduated
                    $student->update(['status' => StudentStatus::Graduated]);

                    Log::info('Attaching student to ceremony', [
                        'student_id' => $student->id,
                        'gpa' => $gpaFloat,
                        'rank' => $rank->value,
                        'email' => $email ?: $student->email,
                        'industry_code' => $industryCode,
                        'industry_name' => $industryName,
                        'citizen_identification' => $citizen_identification,
                        'phone_number' => $phone_number
                    ]);
                    // Attach student to ceremony
                    $ceremony->students()->syncWithoutDetaching([
                        $student->id => [
                            'gpa' => $gpaFloat,
                            'rank' => $rank->value,
                            'email' => $email ?: $student->email,
                            'industry_code' => $industryCode,
                            'industry_name' => $industryName,
                            'citizen_identification' => $citizen_identification,
                            'phone_number' => $phone_number
                        ]
                    ]);

                    $successCount++;
                } catch (Exception $e) {
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                    Log::error('Error importing graduation student', [
                        'row' => $index + 2,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            $importHistory->update([
                'status' => $errors ? StatusImport::PartialyFaild : StatusImport::Completed,
                'successful_records' => $successCount,
                'errors' => $errors ? json_encode($errors) : null,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error importing graduation students', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $importHistory->update([
                'status' => StatusImport::Failed,
                'errors' => json_encode([$e->getMessage()]),
            ]);
        }
    }
}
