<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Warning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncDataWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-data-warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {
            $oldWarning = DB::connection('old_db')
                ->table('warnings')
                ->join('semesters', 'warnings.semester_id', '=', 'semesters.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select('warnings.*', 'semesters.semester', 'school_years.start_year', 'school_years.end_year')
                ->get();
            foreach ($oldWarning as $warning) {
                $studentsWarnings = DB::connection('old_db')
                    ->table('student_warnings')
                    ->join('students', 'student_warnings.student_id', '=', 'students.id')
                    ->select('student_warnings.*', 'students.code')
                    ->where('warning_id', $warning->id)
                    ->pluck('code')
                    ->toArray();

                $schoolYear = SchoolYear::where('start_year', $warning->start_year)->where('end_year', $warning->end_year)->first();
                $semester = Semester::where('semester', $warning->semester)->where('school_year_id', $schoolYear->id)->first();
                $newWarning = Warning::create([
                    'name' => $warning->name,
                    'semester_id' => $semester->id,
                    'faculty_id' => $warning->faculty_id,
                ]);
                $studentIds = Student::whereIn('code', $studentsWarnings)->pluck('id')->toArray();
                $newWarning->students()->sync($studentIds);
                echo 'Warning ' . $warning->name . ' synced successfully' . PHP_EOL;
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $this->info('Sync data warning completed');
    }
}
