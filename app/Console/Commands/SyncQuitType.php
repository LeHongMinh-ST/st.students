<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Quit;
use DB;
use Illuminate\Console\Command;

class SyncQuitType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-quit-type';

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
        $quits = Quit::all();

        foreach ($quits as $quit) {
            DB::table('student_quits')
                ->where('quit_id', $quit->id)
                ->update(['quit_type' => $quit->type]);
        }
    }
}
