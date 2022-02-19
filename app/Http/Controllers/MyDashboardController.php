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

        return view('dashboard', $data);
    }



    public function debug()
    {
        $futCard = new FutCardGeneratorService();
        $futCard->generate();
        dd($futCard);
    }
}
