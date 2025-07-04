<?php

ini_set('display_errors', 'On');

require_once __DIR__ . '/../src/PerlinNoiseGenerator.php';

$gen = new MapGenerator\PerlinNoiseGenerator();

$size = 1000;

$gen->persistence = 0.78;
$gen->size = $size;
$gen->setMapSeed('asd');
$map = $gen->generate();

$image = imagecreatetruecolor($size, $size);

$max = 0;
$min = PHP_INT_MAX;
for ($iy = 0; $iy < $size; $iy++) {
    for ($ix = 0; $ix < $size; $ix++) {
        $h = $map[$iy][$ix];
        if ($min > $h) {
            $min = $h;
        }
        if ($max < $h) {
            $max = $h;
        }
    }
}
$diff = $max - $min;
for ($iy = 0; $iy < $size; $iy++) {
    for ($ix = 0; $ix < $size; $ix++) {
        $h = 255 * ($map[$iy][$ix] - $min) / $diff;
        $color = imagecolorallocate($image, $h, $h, $h);
        imagesetpixel($image, $ix, $iy, $color);
    }
}
imagepng($image, 'visual.png');
imagedestroy($image);