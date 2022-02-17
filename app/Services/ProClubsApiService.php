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

    public function __construct()
    {
        
    }

    public function index()
    {

    }

    static public function doExternalApiCall($endpoint = null, $params = [])
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
                echo "Operation completed without any errors\n";
            }          

            if(curl_errno($curl))
            {
                echo 'Curl error: ' . curl_error($curl);
            }          
                
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;

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

    static public function matchStats($platform, $clubId, $matchType, $cliParams = [])
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => ($matchType) ? $matchType : self::MYCLUB_DEFAULTS['matchType'],
            'platform' => ($platform) ? self::checkValidPlatform($platform) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($clubId) ? $clubId : self::MYCLUB_DEFAULTS['clubId']
        ];

        if ($cliParams) {
            $params = $cliParams;
        }
 
        return self::doExternalApiCall($endpoint, $params);
    }

}