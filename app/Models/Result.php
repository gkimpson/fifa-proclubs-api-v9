<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Str;

class Result extends Model
{
    use HasFactory;

    CONST PAGINATION = 15;
    CONST MATCH_TYPE_LEAGUE = 'gameType9';
    CONST MATCH_TYPE_CUP = 'gameType13';

    // protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'outcome', 'match_date', 'properties', 'platform', 'media'];
    protected $guarded = [];
    protected $appends = [];
    protected $casts = [
        'properties' => 'json'
    ];

    public static function getResults($properties)
    {
        if (!$properties->clubId) {
            abort(404, 'Missing clubId');
        }

        if (!$properties->platform) {
            abort(404, 'Missing platform');
        }        
        
        return Result::where('home_team_id', '=', $properties->clubId)
                    ->orWhere('away_team_id', '=', $properties->clubId)
                    ->orderBy('match_date', 'desc')
                    ->paginate(SELF::PAGINATION);
    }    

    public static function insertUniqueMatches($matches, $platform = null, $showDebugging = true)
    {
        $inserted = 0;

        foreach ($matches as $matchType => $match) {
            // check if existing match already exists in the db, if so don't re-insert this
            if (Result::where('match_id', '=', $match['matchId'])->doesntExist()) {
                $carbonDate = Carbon::now();
                $carbonDate->timestamp($match['timestamp']);
                $clubs = collect($match['clubs'])->values();

                $data = [
                    'match_id' => $match['matchId'],
                    'home_team_id' => $clubs[0]['id'],
                    'away_team_id' => $clubs[1]['id'],
                    'home_team_goals' => $clubs[0]['goals'],
                    'away_team_goals' => $clubs[1]['goals'],
                    'outcome' => self::getMatchOutcome($clubs[0]),
                    'match_date' => $carbonDate->format('Y-m-d H:i:s'),
                    'properties' => [
                        'clubs' => $match['clubs'],
                        'players' => $match['players'],
                        'aggregate' => $match['aggregate'], // aggregate is used for consistency as EA use the same naming convention - this is basically 'team stats' for that match
                    ],
                    'platform' => $platform
                ];
                
                if ($showDebugging) {
                    DB::enableQueryLog();
                    dump($data);
                }
                
                try {
                    Result::create($data);
                    $inserted++;

                    dump('inserted matchId: '. $match['matchId']);
                    
                 } catch (\Exception $e) {
                    dd($e);
                 }

                 if ($showDebugging) {
                    dump(DB::getQueryLog());
                 }

            }
        }
        return $inserted;
    }    

    public static function formatData($data, $params)
    {   
        try {
            $collection = collect(json_decode($data));
            $results = [];
            
            foreach ($collection as $key => $value) {
                $results[] = [
                    'matchId' => $value->matchId,
                    'timestamp' => $value->timestamp,
                    'clubs' => self::getClubsData($value->clubs, $params),
                    'players' => self::getPlayerData($value->players),
                    'aggregate' => $value->aggregate
                ];
            }
            
            return collect($results);
        } catch (\Exception $e) {
            // do some logging...
            dd($e->getMessage());
        }
    }

    private static function getClubsData($clubs, $params) 
    {
        $clubs = collect($clubs);
        $data = [];

        foreach($clubs as $clubId => $club) {
                // try to insert insert club (if this doesn't already exist)
                // if ($clubId == $params->clubIds) {
                //     Club::insertUniqueClub($params, $club);
                // }

                $data[] = [
                    'id' => $clubId,
                    'name' => $club->details->name ?? 'TEAM DISBANDED',
                    'goals' => $club->goals,
                    'goalsAgainst' => $club->goalsAgainst,
                    'seasonId' => $club->seasonId ?? null,
                    'winnerByDnf' => $club->winnerByDnf,
                    'wins' => $club->wins,
                    'losses' => $club->losses,
                    'ties' => $club->ties,
                    'gameNumber' => $club->gameNumber,
                    'result' => $club->result,
                    'teamId' => $club->details->teamId ?? null,
                ];
        }

        return $data;
    }

    private static function getPlayerData($players)
    {
        $players = collect($players);
        
        foreach ($players as $clubId => $clubPlayer) {
            // loop through each player(s) for each club
            foreach ($players[$clubId] as $clubPlayer) {
                $data[$clubId][] = [
                    'assists' => $clubPlayer->assists,
                    'cleansheetsany' => $clubPlayer->cleansheetsany,
                    'cleansheetsdef' => $clubPlayer->cleansheetsdef,
                    'cleansheetsgk' => $clubPlayer->cleansheetsgk,
                    'goals' => $clubPlayer->goals,
                    'goalsconceded' => $clubPlayer->goalsconceded,
                    'losses' => $clubPlayer->losses,
                    'mom' => $clubPlayer->mom,
                    'passattempts' => $clubPlayer->passattempts,
                    'passesmade' => $clubPlayer->passesmade,
                    'pos' => $clubPlayer->pos,
                    'passesmade' => $clubPlayer->passesmade,
                    'realtimegame' => $clubPlayer->realtimegame,
                    'realtimeidle' => $clubPlayer->realtimeidle,
                    'redcards' => $clubPlayer->redcards,
                    'saves' => $clubPlayer->saves,
                    'SCORE' => $clubPlayer->SCORE,
                    'shots' => $clubPlayer->shots,
                    'tackleattempts' => $clubPlayer->tackleattempts,
                    'tacklesmade' => $clubPlayer->tacklesmade,
                    'vproattr' => self::getProAttributes($clubPlayer->vproattr),
                    'vprohackreason' => $clubPlayer->vprohackreason,
                    'wins' => $clubPlayer->wins,
                    'playername' => $clubPlayer->playername,
                    'properties' => $clubPlayer
                ];                
            }
        }

        return $data;
    }  

    private static function getProAttributes($attributes)
    {
        return $attributes;
    }    

    /**
     * get match outcome based on stats from 'home' team (club[0])
     * @clubData array
     * @return $outcome - home win, away win or draw
     */
    private static function getMatchOutcome($clubData)
    {
        if ($clubData['wins'] == 1) {
            $outcome = 'homewin';
        } elseif ($clubData['losses'] == 1) {
            $outcome = 'awaywin';
        } elseif ($clubData['ties'] == 1) {
            $outcome = 'draw';
        }

        return $outcome;
    }    

    static public function getCurrentStreak($clubId, $limit = 30)
    {
        $streaks = [ 'W' => 0, 'L' => 0, 'D' => 0 ];

        // get all results for this clubId order by most recent first
        $results = Result::select(['id', 'home_team_id', 'away_team_id', 'outcome'])
                    ->where('home_team_id', '=', $clubId)
                    ->orWhere('away_team_id', '=', $clubId)
                    ->orderBy('match_date', 'desc')->limit($limit)->get()->toArray();

        $outcomes = [];
        foreach($results as $key => $result) {
            if ($result['home_team_id'] == $clubId && $result['outcome'] == 'homewin' || $result['away_team_id'] == $clubId && $result['outcome'] == 'awaywin') {
                $outcomes[] = 'W';
            } elseif ($result['outcome'] == 'draw') {
                $outcomes[] = 'D';
            } elseif ($result['away_team_id'] == $clubId && $result['outcome'] == 'homewin' || $result['home_team_id'] == $clubId && $result['outcome'] =='awaywin') {
                $outcomes[] = 'L';
            } else {
                throw new \Exception('Unable to process match outcome should be a W, D or L', 1);
            }
        }   

        // $outcomes = array_reverse($outcomes);   // reverse the array.
        $last = array_shift($outcomes);         // shift takes out the first element, but we reversed it, so it's last.  
        $counter = 1;                           // current streak;
        foreach ($outcomes as $result) {        // iterate the array (backwords, since reversed)
            if ($result != $last) break;        // if streak breaks, break out of the loop
            $counter++;                         // won't be reached if broken
        }

        return [
            'streak' => $counter,
            'type' => $last
        ];
    }

    /**
     * get the maximum streaks for a club (W, D and L)
     * @clubId clubId integer
     * @limit limit integer defaults to 20
     * @return $maxStreaks array
     */
    static public function getMaxStreaksByClubId($clubId, $limit = 10000)
    {
        if (!$clubId) {
            throw new \Exception("ClubId required", 1);
        }

        $results = Result::select(['id', 'home_team_id', 'away_team_id', 'outcome'])
                    ->where('home_team_id', '=', $clubId)
                    ->orWhere('away_team_id', '=', $clubId)
                    ->orderBy('match_date', 'desc')->limit($limit)->get()->toArray();

        $outcomes = [];

        foreach($results as $key => $result) {
            if ($result['home_team_id'] == $clubId && $result['outcome'] == 'homewin' || $result['away_team_id'] == $clubId && $result['outcome'] == 'awaywin') {
                $outcomes[] = 'W';
            } elseif ($result['outcome'] == 'draw') {
                $outcomes[] = 'D';
            } elseif ($result['away_team_id'] == $clubId && $result['outcome'] == 'homewin' || $result['home_team_id'] == $clubId && $result['outcome'] =='awaywin') {
                $outcomes[] = 'L';
            } else {
                throw new \Exception('Unable to process match outcome should be a W, D or L', 1);
            }
        }                    
                    
        $streaks = array();
        $prev_value = array('value' => null, 'amount' => null);
        foreach ($outcomes as $val) {
            if ($prev_value['value'] != $val) {
                unset($prev_value);
                $prev_value = array('value' => $val, 'amount' => 0);
                $streaks[] =& $prev_value;
            }
        
            $prev_value['amount']++;
        }

        // get ALL consecutive streaks        
        $collection = collect($streaks)->sortByDesc('amount', SORT_NATURAL);

        $maxStreaks = collect([
            'W' => optional($collection->firstWhere('value', 'W')),
            'D' => optional($collection->firstWhere('value', 'D')),
            'L' => optional($collection->firstWhere('value', 'L'))
        ]);

        return $maxStreaks;
    }

}
