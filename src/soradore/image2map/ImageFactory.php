<?php

namespace soradore\image2map;

class ImageFactory {

    static function cutImage($image){
        $image = \imagecreatefrompng($image);
        if(imagesx($image) % 128 !== 0 || imagesy($image) % 128 !== 0){
            throw new \Exception("can accept 128 * x");
        }
        $cutx = imagesx($image) / 128;
        $cuty = imagesy($image) / 128;
        $images = [];
        for($x=0;$x<$cutx;$x++){
            for($y=0;$y<$cuty;$y++){
                $images[] = \imagecrop($image, ['x' => $x * 128, 'y' => $y * 128,
                                'width' => 128, 'height' => 128
                             ]);
            }
        }
        return $images;
    }
}