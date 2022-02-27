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

class MyDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'results' => Result::getResults($user->properties),
            'myClubId' => $user->properties->clubId,
            'streaks' => [
                'current' => Result::getCurrentStreak($user->properties->clubId),
                'max' => Result::getMaxStreaksByClubId($user->properties->clubId)
            ],
        ];

        // dump($data['results'][0]->media_ids);
        return view('dashboard', $data);
    }


    public function debug()
    {
        $futCard = new FutCardGeneratorService();
        $futCard->generate();
        dd($futCard);
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
