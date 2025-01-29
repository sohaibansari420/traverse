<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hit:route {id}'; // Accepting an ID parameter
    protected $description = 'Hit the /run/my/cron/{id} route';
    // protected $signature = 'daily:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command run daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $url = url("/run/my/cron/{$id}"); // Replace with your actual route
        try {
            $response = Http::get($url);
            \Log::info($url);

            if ($response->successful()) {
                $this->info("Route hit successfully for ID: {$id}");
            } else {
                $this->error("Failed to hit route for ID: {$id}. Status: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
        }

        // info('This is Daily command');
        // rewardRelease();

        // $this->info('Command run daily has been executed successfully');
    }
}
