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
use Alaouy\Youtube\Facades\Youtube;
use App\Models\YoutubePlaylistItem;
use App\Services\YoutubePlaylistItemsService;

class MyDashboardController extends Controller
{
    public $youtubePlaylistId = 'PLMOqwK_eRmiqekWBHzK9sWCli12wZYPt4';

    public function index()
    {
        $user = auth()->user();

        // check for result within the last 15 minutes if none found lets make a call to EA
        if (Result::hasRecentMatchCheck($user->properties->clubId) == false) {
            Artisan::call('proclubsapi:matches n'); // 'n' (no) param removes any output to the browser otherwise it outputs the dumps
        }

        $streaks = Result::getResultsForStreaks($user->properties->clubId);
        $data = [
            'results' => Result::getResults($user->properties),
            'myClubId' => (int)$user->properties->clubId,
            'streaks' => [
                'current' => $streaks['current'],
                'max' => $streaks['max'],
            ],
        ];

        // dd($data['results'][0]->top_rated_players[310718][0]->properties);
        return view('dashboard', $data);
    }

    public function videos($type, $pageToken = null)
    {
        $user = auth()->user();
        $data = [
            'playlistItems' => YoutubePlaylistItemsService::collateYoutubeData($pageToken),
            'players' => YoutubePlaylistItemsService::getPlayerMedia($user->properties->platform, $user->properties->clubId)
        ];
    }

    public function debug()
    {
        $user = auth()->user();
        $players = ProClubsApiService::memberstats($user->properties->platform, $user->properties->clubId);
        $players = collect(json_decode($players));
        $members = collect($players['members']);

        $memberNames = $members->map(function ($member) {
            return $member->name;
        })->filter();

        // dd($memberNames); // collection - CarlosBlackson, MattyhazMSC etc....

        $item = YoutubePlaylistItem::find(2);
        $title = $item->title;
        $game_version = Str::before($title, '_'); // e.g // FIFA_22
        $timestamp = Str::substr(Str::after($title, '_'), 0, 14); // e.g // 20220319002930

        $title_info = Str::between($title, '(', ')');
        $goal_assist_stats = Str::of($title_info)->explode(',');

        $goal_assist_stats = $goal_assist_stats->map(function ($stat) {
            if (Str::contains($stat, 'Goal:') 
             || Str::contains($stat, 'Assist:') ) {
                return trim($stat);
            }
        })->filter();

        if ($goal_assist_stats->isNotEmpty()) {
            $formatted_goal_assist_stats = $goal_assist_stats->mapWithKeys(function ($item, $key) {
                if (Str::contains($item, 'Goal:')) {
                    return [ 'goal' => Str::of($item)->remove('Goal:') ];
                }
    
                if (Str::contains($item, 'Assist:')) {
                    return [ 'assist' => Str::of($item)->remove('Assist:') ];
                }
            });
        }
    
        $game = [
            'game' => $game_version,
            'timestamp' => $timestamp,  // timestamp of game share replay
            'stats' => $formatted_goal_assist_stats ?? []
        ];

        dd($game);
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
        $user = auth()->user();
        $controller = new StatsController();

        $data = [
            'myClubId' => $clubId = $user->properties->clubId,
            'club' => $controller->clubsInfo($request),
            'seasonStats' => $controller->seasonStats($request)
        ];
        
        return view('dashboard.club', $data);
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
