<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Result;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class FutCardGeneratorService 
{
    CONST DEFAULT_FONT_PATH = 'fonts/fifa-2006.ttf';
    CONST FUT_CARD_PATH = 'images/fut-card.jpeg';
    CONST X_AXIS = [
        'left' => 35,
        'right' => 158            
    ];

    CONST Y_AXIS= [
        'top' => 267,
        'middle' => 296,
        'bottom' => 325          
    ];

    CONST MAPPED_POSITIONS = [
        0 => 'GK',
        7 => 'LB',
        5 => 'CB',
        3 => 'RB',
        8 => 'LWB',
        2 => 'RWB',
        10 => 'CDM',
        16 => 'LM',
        14 => 'CM',
        12 => 'RM',
        18 => 'CAM',
        25 => 'ST',
        22 => 'LF',
        21 => 'CF',
        20 => 'RF',
        27 => 'LW',
        23 => 'RW'
    ];

    public static function playerCard($platform, $clubId, $playerName)
    {
        // find player by name 'zabius-uk' for current team & platform
        $data = [];
        $matchLeague = collect(json_decode(ProClubsApiService::matchStats($platform, $clubId, Result::MATCH_TYPE_LEAGUE)));

        // todo - add logic to determine which is the most recent result (cup or league) and use that result
        $players = [];
        if ($matchLeague->has(0) && property_exists($matchLeague[0]->players, $clubId)) {
            $playerStats = $matchLeague[0]->players->{$clubId};

            foreach ($playerStats as $playerId => $player) {
                if (property_exists($player, 'vproattr') && $player->vproattr != 'NH') {
                    $players[$player->playername] = [
                        'vproattr' => $player->vproattr
                    ];
                }
            }
            
            $foundPlayerAttributes = array_key_exists($playerName, $players) ? $players[$playerName] : null;
            dd(self::processAttributes($foundPlayerAttributes['vproattr']));
        }
    }

    private function generateImageBlock($image, $name, $xAxis, $yAxis, $attributes)
    {
        return $image->text($name, $xAxis, $yAxis, function($font) use ($attributes) {
            $font->file(self::DEFAULT_FONT_PATH);
            $font->size($attributes['size']);
            $font->color($attributes['color']);
            $font->align($attributes['align']);
            $font->valign($attributes['valign']);
            $font->angle($attributes['angle']);
        });             
    }    

    public function generate($playerStats = [])
    {
        $img = Image::make(SELF::FUT_CARD_PATH);
        $playerStats = [
            'OVR' => 90,
            'PAC' => 96,
            'SHO' => 81,
            'PAS' => 89,
            'DRI' => 91,
            'DEF' => 55,
            'PHY' => 70,
            'POS' => 'CAM',
            'NAME' => 'GAVIN KIMPSON',
        ];

        $attrs = [
            'size' => 28,
            'color' => '#ffffff',
            'align' => 'center',
            'valign' => 'top',
            'angle' => 0
        ];

        $img = $this->generateImageBlock($img, $playerStats['NAME'], 125, 225, $attrs);
        $attrs['size'] = 26;
        $img = $this->generateImageBlock($img, $playerStats['POS'], 55, 130, $attrs);
        $img = $this->generateImageBlock($img, $playerStats['OVR'], 55, 95, $attrs);

        $attrs['size'] = 24;
        $img = $this->generateImageBlock($img, $playerStats['PAC'], self::X_AXIS['left'], self::Y_AXIS['top'], $attrs);     
        $img = $this->generateImageBlock($img, $playerStats['SHO'], self::X_AXIS['left'], self::Y_AXIS['middle'], $attrs); 
        $img = $this->generateImageBlock($img, $playerStats['PAS'], self::X_AXIS['left'], self::Y_AXIS['bottom'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['top'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['middle'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['bottom'], $attrs);
    
        
        header('Content-Type: image/jpeg');
        $playerImg = Image::make('images/faces/adams.jpg')->resize(150, 150)->greyscale();
        // dump($playerImg);
        // echo $playerImg->encode('jpeg');
        // return $playerImg->response();

        $img->insert($playerImg, 'center', 45, -61);
        echo $img->encode('jpeg');
        return $img->response();        
    }

    static public function getPosition($playerPos)
    {
        dd($playerPos);
    }

    private function attribute_values($attributes, $attribute_group, $attribute_key)
    {
        // dump($attribute_group);

        $collection = collect($attribute_group)->map(function ($key) use ($attributes, $attribute_group, $attribute_key) {
            return $attributes[$key];
        })->reject(function ($key) {
            return empty($key);
        });
        
        return $collection->average();
    }    

    public function formattedPlayerAttributes($attributes)
    {
        // 092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|
        $attributes = '073|069|069|075|068|075|080|088|082|085|092|071|085|089|072|072|072|076|069|097|096|093|075|081|092|089|072|072|073|010|010|010|010|010|';
        $attributes = '092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|';
        // dump($attributes);
        $attributes = Str::of($attributes)->explode('|')->filter()->map(function ($value) {
            return (int)$value;
        });
        dump($attributes);

        $attribute_names = [
            0 => 'Acceleration',
            1 => 'Sprint Speed',
            2 => 'Agility',
            3 => 'Balance',
            4 => 'Jumping',
            5 => 'Stamina',
            6 => 'Strength',
            7 => 'Reactions',
            8 => 'Aggression',
            9 => 'Attack Position',
            10 => 'Ball Control',
            11 => 'Dribbling',
            12 => 'Finishing',
            13 => 'Free Kick Accuracy',
            14 => 'Heading Accuracy',
            15 => 'Shot Power',
            16 => 'Long Shots',
            17 => 'Volleys',
            18 => 'Penalties',
            19 => 'Vision',
            20 => 'Crossing',
            21 => 'Long Pass',
            22 => 'Short Pass',
            23 => 'Curve',
            24 => 'Interceptions',
            25 => 'Marking',
            26 => 'Stand Tackle',
            27 => 'Slide Tackle',
            28 => '?',
            29 => 'GK Diving',
            30 => 'GK Handling',
            31 => 'GK Kicking',
            32 => 'GK Reflexes',
            33 => 'GK Positioning',
        ];

        // 092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|
        $attribute_groups = [
            'shooting' => [12, 13, 14, 15, 16, 17, 18], // finishing, free-kick accuracy, heading accuracy, shot power, long shots, volleys, penalties
            'passing' => [19, 20, 21, 22, 23],  // vision, crossing, long pass, short pass, curve
            'dribbling' => [2, 3, 9, 10, 11], // agility, balance, attack position, ball control, dribbling
            'defending' => [24, 25, 26, 27], // interceptions, marking, stand tackle, slide tackle
            'physical' => [4, 5, 6, 7, 8], // jumping, stamina, strength, reactions, aggression
            'pace' => [0, 1], // acceleration, speed
            'goalkeeping' => [29, 30, 31, 32, 33] // GK only - diving, handling, kicking, reflexes, positioning
        ];   

        // $total = $attributes[28];
        // dump($total);
        
        $attribute_values = [];
        foreach ($attribute_groups as $attribute_key => $attribute_group) {
            $attribute_values[$attribute_key] = round($this->attribute_values($attributes, $attribute_group, $attribute_key), 0);
        }
        
        dd($attribute_values);
    }

    private static function processAttributes($playerAttributes) 
    {
        dd($playerAttributes);
        $formattedPlayerAttributes = [];
        foreach ($playerAttributes as $clubId => $playerAttribute) {
            foreach ($playerAttribute as $playerId => $attributes) {
                $formattedPlayerAttributes[] = [
                    'clubId' => $clubId,
                    'attributes' => self::formattedPlayerAttributes($attributes)
                ];
            }
        }

        // dump($formattedPlayerAttributes);
    }

    /**
     * generate club vpro attributes
     * e.g
     * 310718 => 
     *  236199621 => "092|082|075|078|074|079|090|083|088|083|091|065|068|078|068|067|064|062|081|073|086|089|058|065|093|086|067|067|067|010|010|010|010|010|"]
     */
    private function generateVirtualProAttributes($stats, $matchStats) 
    {
        $playerAttributes = [];
        foreach ($stats as $clubId => $clubStats) {
            foreach($clubStats as $playerId => $playerStats){
                $hasValidProAttrs = (property_exists($playerStats, 'vproattr') 
                && $playerStats->vproattr != 'NH');
                if ($hasValidProAttrs) {
                    $vProAttributes[$playerId] = $playerStats->vproattr;
                    $playerAttributes[$clubId] = $vProAttributes;
                }
            }
        }
        
        $this->processAttributes($playerAttributes);
        return $playerAttributes;
    }    
}