<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetMatchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest matches from the EA Pro Clubs API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //code...
            return 0;
        } catch (\Exception $e) {
            
        }

    }
}
