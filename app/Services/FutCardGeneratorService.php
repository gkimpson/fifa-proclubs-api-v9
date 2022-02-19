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

    //todo - get all the correct mapped positions (member->proPos on member/stats json data)
    CONST MAPPED_POSITIONS = [
        'GK' => '0',
        'LB' => '7',
        'CB' => '5',
        'RB' => '3',
        'LWB' => '8',
        'RWB' => '2',
        'CDM' => '10',
        'LM' => '16',
        'CM' => '14',
        'RM' => '12',
        'CAM' => '18',
        'ST' => '25',
        'LF' => '22',
        'CF' => '21',
        'RF' => '20',
        'LW' => '27',
        'RW' => '23'
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
            'PAC' => 96,
            'SHO' => 81,
            'PAS' => 89,
            'DRI' => 91,
            'DEF' => 55,
            'PHY' => 70,
            'POS' => 'CAM',
            'NAME' => 'fasterAdam',
        ];

        $attrs = [
            'size' => 32,
            'color' => '#ffffff',
            'align' => 'center',
            'valign' => 'top',
            'angle' => 0
        ];

        $img = $this->generateImageBlock($img, $playerStats['NAME'], 125, 70, $attrs);
        $attrs['size'] = 26;
        $img = $this->generateImageBlock($img, $playerStats['POS'], 55, 130, $attrs);

        $attrs['size'] = 24;
        $img = $this->generateImageBlock($img, $playerStats['PAC'], self::X_AXIS['left'], self::Y_AXIS['top'], $attrs);     
        $img = $this->generateImageBlock($img, $playerStats['SHO'], self::X_AXIS['left'], self::Y_AXIS['middle'], $attrs); 
        $img = $this->generateImageBlock($img, $playerStats['PAS'], self::X_AXIS['left'], self::Y_AXIS['bottom'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['top'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['middle'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], self::X_AXIS['right'], self::Y_AXIS['bottom'], $attrs);
    
        
        header('Content-Type: image/jpeg');
        $playerImg = Image::make('images/faces/me.jpg')->resize(100, 100);
        // dump($playerImg);
        // echo $playerImg->encode('jpeg');
        // return $playerImg->response();

        $img->insert($playerImg, 'center', 30, -40);
        echo $img->encode('jpeg');
        return $img->response();        
    }

    static public function getPosition($playerPos)
    {
        dd($playerPos);
    }
}