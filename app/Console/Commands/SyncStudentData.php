<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AdmissionYear;
use App\Models\Student;
use Illuminate\Console\Command;

class SyncStudentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-student-data';

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
        $admissionYears = AdmissionYear::all();
        foreach ($admissionYears as $admissionYear) {
            // Update all students whose student code starts with the admission year (e.g., 63xxxxx)
            Student::whereRaw('LEFT(code, 2) = ?', [$admissionYear->admission_year])
                ->update(['admission_year_id' => $admissionYear->id]);
        }
    }
}
