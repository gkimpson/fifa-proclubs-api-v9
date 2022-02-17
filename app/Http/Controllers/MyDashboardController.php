<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProClubsApiService;
use Illuminate\Support\Facades\Log;
use App\Models\Result;

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

        dump($data);
        return view('dashboard', $data);
    }
}
