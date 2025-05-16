<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedAllYears extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-all-years';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed both school years and admission years';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting to seed all years data...');
        $this->newLine();

        $this->info('Step 1: Seeding school years');
        $this->call('app:seed-school-years');

        $this->newLine();
        $this->info('Step 2: Seeding admission years');
        $this->call('app:seed-admission-years');

        $this->newLine();
        $this->info('All years have been seeded successfully!');

        return Command::SUCCESS;
    }
}
