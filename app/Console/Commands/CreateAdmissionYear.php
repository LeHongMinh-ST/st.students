<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\SchoolHelper;
use App\Models\AdmissionYear;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreateAdmissionYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admission-year';

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
        $schoolYear = Carbon::now()->year;
        $admissionYear = SchoolHelper::calculateAdmissionYear($schoolYear);
        $admissionYear =  AdmissionYear::create([
            'admission_year' => $admissionYear,
            'school_year' => $schoolYear,
        ]);

        return 0;
    }
}
