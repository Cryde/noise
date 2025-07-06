<?php

use Noise\Noise;

$start = microtime(true);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../src/Noise.php';

$size = 1024;
$noise = new Noise();

$memStart = memory_get_usage();

$map = $noise->perlin2DArray($size, $size, 6.0);

echo sprintf('Memory Peak Usage: %sMB', round(memory_get_peak_usage() / 1024 / 1024, 2)) . PHP_EOL;
echo sprintf('Memory Usage: %sMB', round((memory_get_usage() - $memStart) / 1024 / 1024, 2)) . PHP_EOL;
echo sprintf('Time: %s', round(microtime(true) - $start, 3)) . PHP_EOL;