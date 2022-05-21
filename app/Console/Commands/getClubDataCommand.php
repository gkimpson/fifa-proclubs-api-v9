<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ProClubsApiService;
use Illuminate\Console\Command;

class getClubDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:clubdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest matches from the EA Pro Clubs API for a single club';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $matchType = '';
        $gameTypes = ['cup', 'league'];
        $emails = User::all()->pluck('email')->toArray();

        $email = $this->anticipate('Enter your email', $emails);
        if (!in_array($email, $emails)) {
            die('invalid email selected');
        }

        $$matchType = $this->anticipate('Select your Match Type (cup|league)', $gameTypes);
        if (!in_array($$matchType, $gameTypes)) {
            die('invalid game type selected');
        }

        $matchType = 'MATCH_TYPE_'. mb_strtoupper($$matchType);
        $matchTypeConstant = constant("App\Models\Result::$matchType");

        $properties = User::where('email', $email)->pluck('properties')->first();

        if (empty($properties->platform) || (empty($properties->clubId))) {
            die('Cannot retrieve match stats - missing user properties');
        }

        $json = ProClubsApiService::matchStats($properties->platform, $properties->clubId, $matchTypeConstant);
        dump($json);

        return 0;
    }
}
