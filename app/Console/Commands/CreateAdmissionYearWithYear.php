<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AdmissionYear;
use App\Supports\SchoolHelper;
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
        AdmissionYear::create([
            'admission_year' => $admissionYear,
            'school_year' => $schoolYear,
        ]);
        return 0;
    }
}
