<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\SchoolHelper;
use App\Models\AdmissionYear;
use Illuminate\Console\Command;

class CreateAdmissionYearWithYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admission-year-with-year {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schoolYear = $this->argument('year');
        $admissionYear = SchoolHelper::calculateAdmissionYear((int)$schoolYear);

        // Check if admission year already exists
        $exists = AdmissionYear::where('school_year', $schoolYear)->exists();

        if ($exists) {
            $this->info("Admission year for school year {$schoolYear} already exists. Skipping.");
            return 0;
        }

        // Create new admission year
        AdmissionYear::updateOrCreate(
            ['school_year' => $schoolYear],
            ['admission_year' => $admissionYear]
        );

        $this->info("Created admission year {$admissionYear} for school year {$schoolYear}.");
        return 0;
    }
}
