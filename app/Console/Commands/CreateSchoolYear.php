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

        $schoolYear = SchoolYear::updateOrCreate([
            'start_year' => $startYear,
            'end_year' => $endYear,
        ]);

        Semester::updateOrCreate([
            'semester' => 1,
            'school_year_id' => $schoolYear->id,
        ]);

        Semester::updateOrCreate([
            'semester' => 2,
            'school_year_id' => $schoolYear->id,
        ]);
    }
}
