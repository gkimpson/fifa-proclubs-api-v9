<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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

    public static function playerCard($playerName)
    {
        // find player by name 'zabius-uk' for current team
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
}