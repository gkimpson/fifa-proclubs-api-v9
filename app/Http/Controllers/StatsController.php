<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProClubsApiService;


class StatsController extends Controller
{
    public $apiUrl = 'https://proclubs.ea.com/api/fifa/';
    public $referer = 'https://www.ea.com/';
    public $user;
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

    const MATCH_TYPES = [
        'gameType9' => 'league',
        'gameType13' => 'cup'
    ];

    public function index()
    {

    }

    public function clubsInfo(Request $request)
    {
        return ProClubsApiService::clubsInfo($request->input('platform'), $request->input('clubIds'));
    }

    private function doExternalApiCall($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
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
    }    
}
