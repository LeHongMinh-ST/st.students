<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SeedAdmissionYears extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-admission-years';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed admission years from 2003 to current year';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startYear = 2003;
        $currentYear = Carbon::now()->year;

        $this->info('Seeding admission years from ' . $startYear . ' to ' . $currentYear);
        $this->newLine();

        $bar = $this->output->createProgressBar($currentYear - $startYear + 1);
        $bar->start();

        for ($year = $startYear; $year <= $currentYear; $year++) {
            // Call the existing command to create admission year
            $this->call('app:create-admission-year-with-year', [
                'year' => $year,
            ], $this->output->isQuiet());

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('All admission years have been seeded successfully!');

        return Command::SUCCESS;
    }
}
