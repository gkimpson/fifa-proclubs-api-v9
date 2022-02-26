<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProClubsApiService 
{
    const API_URL = 'https://proclubs.ea.com/api/fifa/';
    const REFERER = 'https://www.ea.com/';
    const PLATFORMS = [
        'xbox-series-xs',
        'xboxone',
        'ps5',
        'ps4',
        'pc'
    ];

    const MYCLUB_DEFAULTS = [
        'platform' => 'ps5',
        'clubId' => '310718',
        'clubName' => 'Banterbury FC',
        'matchType' => 'gameType9' // (gameType13 = cup, gameType9 = league)
    ];

    const LEADERBOARDS = [
        'clubRankLeaderboard', 
        'seasonRankLeaderboard'
    ];

    public function __construct()
    {
        
    }

    public function index()
    {

    }

    /** not currently working... */
    static private function doExternalApiCallGuzzle($endpoint = null, $params = null)
    {
        // $url = self::API_URL . $endpoint . http_build_query($params);
        // return Http::withHeaders(['Referer' => self::REFERER])->get($url)->json();

        // $response = Http::withHeaders([
        //     'accept-language' => 'en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7',
        //     'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
        //     'http'
        // ])->withOptions([
        //     'debug' => true
        // ])->get( self::API_URL . $endpoint, [
        //     'name' => 'Zabs'
        // ]);
    }    

    static public function doExternalApiCall($endpoint = null, $params = [], $jsonDecoded = false, $isCLI = false)
    {
        try {
            $url = self::API_URL . $endpoint . http_build_query($params);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                // CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
                // CURLOPT_CUSTOMREQUEST => 'GET',
                // CURLOPT_VERBOSE => false,
                CURLOPT_FAILONERROR => true,
                CURLOPT_HTTPHEADER => array(
                    "accept-language: en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7",
                    "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36",
                ),
            ));

            if(curl_exec($curl) === false)
            {
                echo 'Curl error: ' . curl_error($curl);
            }
            else
            {
                if ($isCLI) {
                    echo "Operation completed without any errors\n";
                }
            }          

            if(curl_errno($curl))
            {
                echo 'Curl error: ' . curl_error($curl);
            }   
                       
            $response = curl_exec($curl);
            curl_close($curl);
            return ($jsonDecoded) ? json_decode($response) : $response;

        } catch (\Exception $e) {
            // do some logging...
            return false;
        }        
    }

    /**
     * check valid platform has been added to request
     */
    static private function checkValidPlatform($platform = null)
    {
        if (!in_array($platform, self::PLATFORMS)) {
            abort(400, "{$platform} is an invalid platform");
        }
        
        return $platform;
    }    

    static public function clubsInfo($platform, $clubId)
    {
        $endpoint = 'clubs/info?';
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ]; 
       
        return self::doExternalApiCall($endpoint, $params);
    }

    static public function matchStats($platform, $clubId, $matchType)
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => ($matchType) ? $matchType : self::MYCLUB_DEFAULTS['matchType'],
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ];

        return self::doExternalApiCall($endpoint, $params);
    }

    static public function careerStats($platform, $clubId, $raw = false)
    {           
        $endpoint = 'members/career/stats?';
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubId' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ];     
           
        return self::doExternalApiCall($endpoint, $params);
    }

    static public function memberstats($platform, $clubId)
    {
        $endpoint = 'members/stats?';
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubId' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ];     

        return self::doExternalApiCall($endpoint, $params);
    }

    static public function formatMembersData($membersData)
    {
        // find a more laravel way to do this...
        $collection = collect($membersData);
        $membersData = $collection->map(function ($item, $key) {
            $item->proPosition = FutCardGeneratorService::MAPPED_POSITIONS[$item->proPos];
            return $item;
        });      

        $membersData = $membersData->sortBy([
            ['proOverall', 'desc'],
            ['name', 'asc']
        ]);

        return $membersData;
    }

    static public function seasonStats($platform, $clubId)
    {
        $endpoint = 'clubs/seasonalStats?';
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ];    
           
        return self::doExternalApiCall($endpoint, $params);        
    }    

    static public function settings()
    {
        $endpoint = 'settings?';
        return self::doExternalApiCall($endpoint);
    }

    static public function search($platform, $clubName)
    {
        $endpoint = 'clubs/search?';
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubName' => ($clubName) ? $clubName : self::MYCLUB_DEFAULTS['clubName']
        ];  

        $items = collect(json_decode(self::doExternalApiCall($endpoint, $params)));
    
        $results = [];
        foreach($items as $clubId => &$item) {
            if (property_exists($item, 'seasons')) {
                if (property_exists($item, 'clubInfo') && property_exists($item->clubInfo, 'teamId')) {
                    $teamId = $item->clubInfo->teamId;
                    $results[] = array(
                        'item' => $item,
                        'customCrestUrl' => "https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/l{$teamId}.png",
                    );
                } else {
                    $results[] = array(
                        'item' => $item,
                        'customCrestUrl' => "https://media.contentapi.ea.com/content/dam/ea/fifa/fifa-21/pro-clubs/common/pro-clubs/crest-default.png",
                    );                    
                }

            } else {
                // team has yet to play a season so won't be a valid option
            }
        }

        return($results);
    }

    static public function leaderboard($platform, $type)
    {
        $endpoint = match ($type) {
            'club' => 'clubRankLeaderboard?',
            'season' => 'seasonRankLeaderboard?',
            default => 'clubRankLeaderboard?'
        };        

        if (!$endpoint) {
            abort(400, 'Invalid leaderboard type');
        }
        
        $params = [
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform']
        ];

        return self::doExternalApiCall($endpoint, $params);         
    }

}