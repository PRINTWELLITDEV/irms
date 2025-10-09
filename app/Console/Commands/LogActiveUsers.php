<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-active-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logs the current active users from rsusers table';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $this->info("Running log:active-users...");

        $results = DB::connection('sqlsrv')->select('EXEC sp_active_users_per_site');

        if (empty($results)) {
            $this->warn("No active users found.");
        } else {
            foreach ($results as $row) {
                $this->info("Site: {$row->rssite} | Active: {$row->total_active_users}");
            }
        }
    }

}
