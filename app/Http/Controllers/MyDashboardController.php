<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProClubsApiService;
use Illuminate\Support\Facades\Log;
use App\Models\Result;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;
use App\Services\FutCardGeneratorService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Artisan;

class MyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // check for result within the last 15 minutes if none found lets make a call to EA
        if (Result::hasRecentMatchCheck($user->properties->clubId) == false) {
            Artisan::call('proclubsapi:matches n'); // 'n' (no) param removes any output to the browser otherwise it outputs the dumps
        }

        $filters = [
          'start' => $request->query('start') ?? null,
          'end' => $request->query('end') ?? null,
        ];

        $streaks = Result::getResultsForStreaks($user->properties->clubId);
        $data = [
            'results' => Result::getResults($user->properties, $filters),
            'myClubId' => (int)$user->properties->clubId,
            'streaks' => [
                'current' => $streaks['current'],
                'max' => $streaks['max'],
            ],
            'breadcrumbs' => [
                'dashboard' => [
                    'name' => 'Dashboard'
                ],
            ]
        ];

//        dump($data['results'][1]->team_player_counts);
        // dd($data['results'][0]->top_rated_players[310718][0]->properties);
        return view('dashboard', $data);
    }

    public function debug()
    {
        $user = auth()->user();
        $futCard = new FutCardGeneratorService();
        $futCard->generate();

        Result::getPlayersRecentForm($user->properties);
    }

    public function cup()
    {
        $user = auth()->user();
        $data = [];
        return view('dashboard.matches', $data);
    }

    public function league()
    {
        $user = auth()->user();
        $data = [];
        return view('dashboard.matches', $data);
    }

    public function squad(Request $request)
    {
        $user = auth()->user();
        $controller = new StatsController();

        $data = [
            'careerStats' => $controller->careerStats($request),
            'seasonStats' => $controller->seasonStats($request)
        ];

        return view('dashboard.squad', $data);
    }

    public function club(Request $request)
    {
        $apiData = json_decode(ProClubsApiService::memberStats($request->input('platform'), $request->input('clubId')));
        $user = auth()->user();
        $data = [
            'members' => ProClubsApiService::formatMembersData($apiData->members),
            'positions' => $apiData->positionCount,
            'platform' => $user->properties->platform,
            'clubId' => $user->properties->clubId,
            'myClubId' => $user->properties->clubId,
            'breadcrumbs' => [
                'club' => [
                    'name' => 'Club'
                ],
            ]
        ];

        return view('dashboard.members', $data);
    }

    public function form()
    {
        $user = auth()->user();
        $data = [];
        return view('dashboard.form', $data);
    }

    public function rank()
    {
        $user = auth()->user();
        $data = [];
        return view('dashboard.rank', $data);
    }

    public function media()
    {
        $user = auth()->user();
        $platform = $user->properties->platform;
        $clubId = $user->properties->clubId;
        $media = Result::getMedia($platform, $clubId);

        $data = [
            'media' => $media['pagination'],
            'formatted' => $media['formatted']
        ];

        return view('dashboard.media', $data);
    }

}
