<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;

class BackfillPlayerCountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:backfillcounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform backfill on - player counts (results table)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updated = Result::backfillPlayerCounts();
        $this->info('Updated '. $updated .' rows in the results table');
        return 0;
    }
}
