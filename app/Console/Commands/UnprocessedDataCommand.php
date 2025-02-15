<?php

namespace App\Console\Commands;

use App\Models\UnprocessedData;
use Illuminate\Console\Command;

class UnprocessedDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unprocessed:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run unprocessed jobs in table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('This is Unprocessed Data command');
        $unprocessedDatas = UnprocessedData::where('is_processed', 0)->get();

        foreach ($unprocessedDatas as $unprocessedData) {

            if (now() >= $unprocessedData->created_at->addHours($unprocessedData->time_period_hours)) {
                $unprocessedData->update(['is_processed' => 1]);

                call_user_func_array($unprocessedData->method, json_decode($unprocessedData->data));
            }
        }

        $this->info('Run unprocessed jobs has been executed successfully');
    }
}
