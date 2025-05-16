<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SeedSchoolYears extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-school-years';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed school years from 1956 to current year';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startYear = 1956;
        $currentYear = Carbon::now()->year;

        $this->info('Seeding school years from ' . $startYear . ' to ' . $currentYear);
        $this->newLine();

        $bar = $this->output->createProgressBar($currentYear - $startYear + 1);
        $bar->start();

        for ($year = $startYear; $year <= $currentYear; $year++) {
            // Call the existing command to create school year
            $this->call('app:create-school-year', [
                '--start_year' => $year,
            ], $this->output->isQuiet());

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('All school years have been seeded successfully!');

        return Command::SUCCESS;
    }
}
