<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProClubsApiService;
use App\Models\User;
use App\Models\Result;
use Illuminate\Support\Facades\Log;

class GetMatchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:matches {output=y}';

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
            ray()->measure();
            $showOutput = ($this->argument('output') === 'y') ? true : false;
            $showDebugging = false;
            $this->info('Running...' . $this->description);
            $properties = User::pluck('properties')->unique();

            $results = [];
            foreach ($properties as $key => $property) {
                $this->info("[{$key}] Collecting matches data for - {$property->platform}/{$property->clubId}");
                $results_1 = Result::formatData($this->handleResultByMatchType(Result::MATCH_TYPE_LEAGUE, $property), $property);
                $results_2 = Result::formatData($this->handleResultByMatchType(Result::MATCH_TYPE_CUP, $property), $property);
                $results = array_merge($results_1->toArray(), $results_2->toArray());
                $count = count($results);
                if ($showOutput) {
                    $this->info("{$count} matches found");    
                }
                
                $inserted = Result::insertUniqueMatches($results, $property->platform, $showDebugging, $showOutput);
                if ($showOutput) {
                    $this->info("{$inserted} unique results into the database"); 
                }
            }
            ray()->measure();
            
            return 0;
        } catch (\Exception $e) {
            log::error($e->getMessage());
        }
    }

    private function handleResultByMatchType($matchType, $properties)
    {
        return ProClubsApiService::matchStats($properties->platform, $properties->clubId, $matchType);
    }
}
