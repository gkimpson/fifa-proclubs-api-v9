<?php

namespace App\Http\Controllers;

use App\Services\FutCardGeneratorService;
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
        $apiData = json_decode(ProClubsApiService::memberStats($request->input('platform'), $request->input('clubId')));
        $data = [
            'members' => ProClubsApiService::formatMembersData($apiData->members),
            'positions' => $apiData->positionCount
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
            'card' => FutCardGeneratorService::playerCard($platform, $clubId, $playerName)
        ];
        dd($data);
        return view('stats.player', $data);
    }

    public function player()
    {
        
    }
}
