<?php

namespace App\Http\Controllers;

use App\Services\FutCardGeneratorService;
use Illuminate\Http\Request;
use App\Services\ProClubsApiService;
use App\Models\Result;
use Illuminate\Support\Str;


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
        $apiData = json_decode(ProClubsApiService::memberStats($request->input('platform'), $request->input('clubId')));
        $user = auth()->user();    
        $data = [
            'members' => ProClubsApiService::formatMembersData($apiData->members),
            'positions' => $apiData->positionCount,
            'platform' => $user->properties->platform,
            'clubId' => $user->properties->clubId,
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

    public function playerCard($platform = null, $clubId = null, $playerName = null)
    {
        $data = [
            // 'card' => FutCardGeneratorService::playerCard($platform, $clubId, $playerName)
            'chart' => 'chart'
        ];
        
        return view('stats.player', $data);
    }

    public function player()
    {
        
    }

    /**
     * save youtube highlights for match
     */
    public function highlights(Request $request)
    {
        $matchId = $request->formData['matchId'];
        $url = $request->formData['youtubeURL'];
        $youtubeId = Str::remove('https://www.youtube.com/watch?v=', $url);
        $result = Result::where('match_id', $matchId)->first();
        $result->media .= $youtubeId .',';
        $result->save();
        // dd($result, $result->save());
    }    
}
