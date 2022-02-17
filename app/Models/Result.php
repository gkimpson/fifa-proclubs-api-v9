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
    protected $appends = ['my_club_home_or_away', 'team_ids', 'home_team_crest_url', 'away_team_crest_url', 'match_data', 'media_ids'];
    protected $casts = [
        'properties' => 'json'
    ];    

    public static function insertUniqueMatches($matches, $platform = null, $showOutput = false)
    {
        $inserted = 0;
        $failedToInsert = 0;
        $start_time = microtime(TRUE);

        foreach ($matches as $matchType => $match) {
            // dd($match, __LINE__);
            // check if existing match already exists in the db, if so don't re-insert this
            // dd($match->matchId);
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
                
                // DB::enableQueryLog();
                // if ($showOutput) {
                //     dump($data);
                // }
                
                try {
                    Result::create($data);
                    $inserted++;

                    if ($showOutput) {
                        dump('inserted matchId: '. $match['matchId']);
                    }
                    
                 } catch (\Exception $e) {
                    dd($e);
                 }
                // dd(DB::getQueryLog());           
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
}
