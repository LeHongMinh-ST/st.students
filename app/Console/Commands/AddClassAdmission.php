<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AdmissionYear;
use App\Models\ClassGenerate;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddClassAdmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-class-admission';

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

        $classes = ClassGenerate::all();

        DB::beginTransaction();
        try {

            foreach ($classes as $class) {
                if (preg_match('/\d+/', $class->code, $matches)) {
                    $admission = $matches[0];

                    $admissionYear = AdmissionYear::where('admission_year', $admission)->first();

                    if ($admissionYear) {
                        $class->admission_year_id = $admissionYear->id;
                        $class->save();
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
        }
    }
}
