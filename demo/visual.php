<?php

use Noise\Noise;

ini_set('display_errors', 'On');

require_once __DIR__ . '/../src/Noise.php';

$size = 1024;
$noise = new Noise();
$map = $noise->perlin2DArray(
    width: $size,
    height: $size,
    scale: 4.0,
    seed: 42
);

$max = 0;
$min = PHP_INT_MAX;
foreach ($map as $value) {
    if ($min > $value) {
        $min = $value;
    }
    if ($max < $value) {
        $max = $value;
    }
}

$diff = $max - $min;
$image = imagecreatetruecolor($size, $size);
for ($iy = 0; $iy < $size; $iy++) {
    for ($ix = 0; $ix < $size; $ix++) {
        $h = 255 * ($map[$iy * $size + $ix] - $min) / $diff;
        $color = imagecolorallocate($image, $h, $h, $h);
        imagesetpixel($image, $ix, $iy, $color);
    }
}

imagepng($image, 'visual.png');
imagedestroy($image);
