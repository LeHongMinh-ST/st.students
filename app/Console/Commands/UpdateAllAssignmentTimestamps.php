<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ClassAssign;
use App\Models\ClassStudent;
use Illuminate\Console\Command;

class UpdateAllAssignmentTimestamps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-all-assignment-timestamps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update assigned_at timestamps for all assignments using created_at';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Updating assigned_at timestamps for all assignments...');

        // Update class_students table using Eloquent
        $classStudents = ClassStudent::all();
        $classStudentsCount = 0;

        foreach ($classStudents as $classStudent) {
            $classStudent->assigned_at = $classStudent->created_at;
            $classStudent->save();
            $classStudentsCount++;
        }

        $this->info("Updated {$classStudentsCount} records in class_students table.");

        // Update class_assigns table using Eloquent
        $classAssigns = ClassAssign::all();
        $classAssignsCount = 0;

        foreach ($classAssigns as $classAssign) {
            $classAssign->assigned_at = $classAssign->created_at;
            $classAssign->save();
            $classAssignsCount++;
        }

        $this->info("Updated {$classAssignsCount} records in class_assigns table.");

        $this->info('Assignment timestamps update completed successfully!');

        return Command::SUCCESS;
    }
}
