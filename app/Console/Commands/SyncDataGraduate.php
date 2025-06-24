<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\GraduationCeremony;
use App\Models\Student;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDataGraduate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-data-graduate';

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
            $oldGraduate = DB::connection('old_db')
                ->table('graduation_ceremonies')
                ->select('graduation_ceremonies.*')
                ->get();
            foreach ($oldGraduate as $graduate) {
                $graduateStudents = DB::connection('old_db')
                    ->table('graduation_ceremony_students')
                    ->join('students', 'students.id', '=', 'graduation_ceremony_students.student_id')
                    ->where('graduation_ceremony_id', $graduate->id)
                    ->select('graduation_ceremony_students.*', 'students.code')
                    ->get();
                $newGraduate = GraduationCeremony::create([
                    'name' => $graduate->name,
                    'school_year' => $graduate->year,
                    'certification' => $graduate->certification,
                    'certification_date' => $graduate->certification_date,
                    'faculty_id' => 1,
                ]);
                $studentIds = [];
                foreach ($graduateStudents as $item) {
                    $student = Student::where('code', $item->code)->first();
                    if ($student) {
                        $studentIds[$student->id] = [
                            'gpa' => $item->gpa,
                            'rank' => $item->rank,
                            'email' => $item->email ?? null,
                        ];
                    }
                }
                $newGraduate->students()->sync($studentIds);
                $this->info('Sync data graduate ' . $graduate->name . ' success');
            }
            $this->info('Sync data graduate success');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
