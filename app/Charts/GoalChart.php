<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Result;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class GoalChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $user = auth()->user();
        $stats = \App\Models\Result::getGoalStats($user->properties);
        $labels = $stats['labels']->toArray();
        $data = $stats['data']->toArray();

        return Chartisan::build()
            ->labels($labels)
            ->dataset('Goals p/match', $data);
    }
}