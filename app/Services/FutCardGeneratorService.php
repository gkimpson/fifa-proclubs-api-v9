<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FutCardGeneratorService 
{
    public $defaultFontPath = 'fonts/fifa-2006.ttf';
    public $futCardPath = 'images/fut-card.jpeg';

    public function __construct()
    {
        $this->xAxis = [
            'left' => 35,
            'right' => 158            
        ];

        $this->yAxis = [
            'top' => 267,
            'middle' => 296,
            'bottom' => 325          
        ];
    }

    private function generateImageBlock($image, $name, $xAxis, $yAxis, $attributes)
    {
        return $image->text($name, $xAxis, $yAxis, function($font) use ($attributes) {
            $font->file($this->defaultFontPath);
            $font->size($attributes['size']);
            $font->color($attributes['color']);
            $font->align($attributes['align']);
            $font->valign($attributes['valign']);
            $font->angle($attributes['angle']);
        });             
    }    

    public function generate($playerStats = [])
    {
        $img = Image::make($this->futCardPath);
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
        $img = $this->generateImageBlock($img, $playerStats['PAC'], $this->xAxis['left'], $this->yAxis['top'], $attrs);     
        $img = $this->generateImageBlock($img, $playerStats['SHO'], $this->xAxis['left'], $this->yAxis['middle'], $attrs); 
        $img = $this->generateImageBlock($img, $playerStats['PAS'], $this->xAxis['left'], $this->yAxis['bottom'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], $this->xAxis['right'], $this->yAxis['top'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], $this->xAxis['right'], $this->yAxis['middle'], $attrs);
        $img = $this->generateImageBlock($img, $playerStats['DRI'], $this->xAxis['right'], $this->yAxis['bottom'], $attrs);
    
        
        header('Content-Type: image/jpeg');
        $playerImg = Image::make('images/faces/me.jpg')->resize(100, 100);
        // dump($playerImg);
        // echo $playerImg->encode('jpeg');
        // return $playerImg->response();

        $img->insert($playerImg, 'center', 30, -40);
        echo $img->encode('jpeg');
        return $img->response();        
    }
}