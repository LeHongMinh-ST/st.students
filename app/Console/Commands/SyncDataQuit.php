<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Quit;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncDataQuit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-data-quit';

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
            $oldQuit = DB::connection('old_db')
                ->table('quits')
                ->select('quits.*')
                ->get();
            foreach ($oldQuit as $quit) {
                $studentsQuit = DB::connection('old_db')
                    ->table('student_quits')
                    ->join('students', 'student_quits.student_id', '=', 'students.id')
                    ->select('student_quits.*', 'students.code')
                    ->where('quit_id', $quit->id)->get();
                $newQuit = Quit::create([
                    'name' => $quit->name,
                    'faculty_id' => $quit->faculty_id,
                    'school_year' => $quit->year,
                    'decision_number' => $quit->certification,
                    'decision_date' => $quit->certification_date,
                    'type' => $quit->type,
                ]);
                $data = [];
                foreach ($studentsQuit as $studentQuit) {
                    $student = Student::where('code', $studentQuit->code)->first();
                    $noteQuit = $studentQuit->note_quit;
                    if (!$noteQuit) {
                        $noteQuit = 'Thôi học, chuyển ngành sang khoa khác';
                    }
                    $data[$student->id] = ['note_quit' => $noteQuit];
                }
                $newQuit->students()->sync($data);
                echo 'Quit ' . $quit->name . ' synced successfully' . PHP_EOL;
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
