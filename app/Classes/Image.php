<?php

namespace App\Classes;

class Image
{
    public $image;
    private static $instance = null;

    public function make($image)
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        self::$instance->image = $image;

        return self::$instance;
    }

    public function save($filename)
    {
        $maxWidth  = config('image_max_width');
        $maxHeight = config('image_max_height');

        list($width, $height, $type, $attr) = getimagesize( $this->image );

        $size = getimagesize($this->image);

        if ($size) {
            $imageWidth  = $size[0];
            $imageHeight = $size[1];
            $wRatio = $imageWidth / $maxWidth;
            $hRatio = $imageHeight / $maxHeight;
            $maxRatio = max($wRatio, $hRatio);

            if ($maxRatio > 1) {
                $outputWidth = $imageWidth / $maxRatio;
                $outputHeight = $imageHeight / $maxRatio;
            } else {
                $outputWidth = $imageWidth;
                $outputHeight = $imageHeight;
            }
        }

        $src = imagecreatefromstring( file_get_contents( $this->image ) );
        $dst = imagecreatetruecolor( $outputWidth, $outputHeight );
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $outputWidth, $outputHeight, $width, $height );
        imagedestroy( $src );
        $actualFilename = get_actual_path(image_relative_path($filename));
        imagepng( $dst, $actualFilename ); // adjust format as needed
        imagedestroy( $dst );

        return $actualFilename;
    }
}
