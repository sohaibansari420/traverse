<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        rewardRelease();

        $this->info('Command run daily has been executed successfully');
    }
}
