<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProClubsApiService;


class StatsController extends Controller
{
    public function index()
    {

    }

    public function clubsInfo(Request $request)
    {
        return ProClubsApiService::clubsInfo($request->input('platform'), $request->input('clubIds'));
    }

    public function matchStats(Request $request)
    {
        return ProClubsApiService::matchStats($request->input('platform'), $request->input('clubId'), $request->input('matchType'));
    }

    public function careerStats(Request $request)
    {
        $data = [
            'stats' => ProClubsApiService::careerStats($request->input('platform'), $request->input('clubId'))
        ];
        return view('stats.careers', $data); 
    }

    public function memberStats(Request $request)
    {
        $data = [
            'stats' => ProClubsApiService::memberStats($request->input('platform'), $request->input('clubId'))
        ];
        return view('stats.members', $data);
    }    

    public function seasonStats(Request $request)
    {
        return ProClubsApiService::seasonStats($request->input('platform'), $request->input('clubId'));
    }

    public function search(Request $request)
    {
        return ProClubsApiService::search($request->input('platform'), $request->input('clubName'));
    }    
   
    public function settings(Request $request)
    {
        return ProClubsApiService::settings($request);
    }      

    public function leaderboard(Request $request)
    {
        return ProClubsApiService::leaderboard($request->input('platform'), $request->input('type'));
    }

    // private function doExternalApiCall($endpoint = null, $params = [], $isCLI = false)
    // {
    //     $url = $this->apiUrl . $endpoint . http_build_query($params);
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         // CURLOPT_MAXREDIRS => 5,
    //         CURLOPT_TIMEOUT => 10,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
    //         // CURLOPT_CUSTOMREQUEST => 'GET',
    //         // CURLOPT_VERBOSE => false,
    //         CURLOPT_FAILONERROR => true,
    //         CURLOPT_HTTPHEADER => array(
    //             "accept-language: en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7",
    //             "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36",
    //         ),
    //       ));

    //       if(curl_exec($curl) === false)
    //       {
    //           echo 'Curl error: ' . curl_error($curl);
    //       }
    //       else
    //       {
    //           if ($isCLI) {
    //             echo "Operation completed without any errors\n";
    //           }
    //       }          

    //       if(curl_errno($curl))
    //       {
    //           echo 'Curl error: ' . curl_error($curl);
    //       }          
          
    //       $response = curl_exec($curl);
    //       curl_close($curl);
    //       return $response;
    // }    
}
