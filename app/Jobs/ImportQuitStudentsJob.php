<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatusImport;
use App\Enums\StudentStatus;
use App\Models\ImportHistory;
use App\Models\Quit;
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

class ImportQuitStudentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $userId,
        private readonly int $importHistoryId,
        private readonly int $quitId
    ) {
    }

    public function handle(): void
    {
        $importHistory = ImportHistory::find($this->importHistoryId);
        if (!$importHistory) {
            Log::error('Import history not found', ['id' => $this->importHistoryId]);
            return;
        }

        $quit = Quit::find($this->quitId);
        if (!$quit) {
            Log::error('Quit not found', ['id' => $this->quitId]);
            $importHistory->update(['status' => StatusImport::Failed]);
            return;
        }

        try {
            $importHistory->update(['status' => StatusImport::Processing]);

            $filePath = Storage::path($importHistory->path);
            $rows = Excel::toArray(new QuitStudentPreviewImport(), $filePath)[0];

            // Remove header row
            array_shift($rows);

            $successCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    $studentCode = $row[0] ?? null;
                    $fullName = $row[1] ?? null;
                    $quitType = $row[2] ?? null;
                    $reason = $row[3] ?? null;

                    if (!$studentCode || !$fullName) {
                        $errors[] = "Dòng " . ($index + 2) . ": Thiếu mã sinh viên hoặc họ tên";
                        continue;
                    }

                    // Find student by code
                    $student = Student::where('code', $studentCode)->first();

                    if (!$student) {
                        $errors[] = "Dòng " . ($index + 2) . ": Không tìm thấy sinh viên với mã " . $studentCode;
                        continue;
                    }

                    // Determine quit type
                    $quitTypeValue = match (mb_strtolower(trim($quitType))) {
                        'tự thôi học', 'thôi học' => StudentStatus::ToDropOut->value,
                        'tạm dừng', 'tạm dừng học tập' => StudentStatus::TemporarilySuspended->value,
                        'đuổi học', 'buộc thôi học' => StudentStatus::Expelled->value,
                        default => StudentStatus::ToDropOut->value,
                    };

                    // Update student status
                    $student->update(['status' => $quitTypeValue]);

                    // Attach student to quit
                    $quit->students()->syncWithoutDetaching([
                        $student->id => [
                            'quit_type' => $quitTypeValue,
                            'note_quit' => $reason,
                        ]
                    ]);

                    $successCount++;
                } catch (Exception $e) {
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                    Log::error('Error importing quit student', [
                        'row' => $index + 2,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            $importHistory->update([
                'status' => $errors ? StatusImport::PartiallySuccessful : StatusImport::Successful,
                'successful_records' => $successCount,
                'errors' => $errors ? json_encode($errors) : null,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error importing quit students', [
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
