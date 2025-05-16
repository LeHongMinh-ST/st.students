<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreateSchoolYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-school-year {--start_year=}';

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
        $startYear = $this->option('start_year') ?? Carbon::now()->format('Y');
        $endYear = (int) $startYear + 1;

        // Check if school year already exists
        $exists = SchoolYear::where('start_year', $startYear)
            ->where('end_year', $endYear)
            ->exists();

        if ($exists) {
            $this->info("School year {$startYear}-{$endYear} already exists. Updating semesters if needed.");
        } else {
            $this->info("Creating new school year {$startYear}-{$endYear}.");
        }

        $schoolYear = SchoolYear::updateOrCreate([
            'start_year' => $startYear,
            'end_year' => $endYear,
        ]);

        // Create or update semesters
        $this->info("Creating or updating semesters for school year {$startYear}-{$endYear}.");
        Semester::updateOrCreate(
            [
                'semester' => 1,
                'school_year_id' => $schoolYear->id,
            ],
            [
                'semester' => 1,
                'school_year_id' => $schoolYear->id,
            ]
        );

        Semester::updateOrCreate(
            [
                'semester' => 2,
                'school_year_id' => $schoolYear->id,
            ],
            [
                'semester' => 2,
                'school_year_id' => $schoolYear->id,
            ]
        );

        $this->info("Semesters for school year {$startYear}-{$endYear} have been created or updated.");
    }
}
