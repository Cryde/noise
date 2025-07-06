# Noise functions

## Description
Generate array of noise values.

This package replicate some noise functions part of https://github.com/mario-deluna/php-glfw but in raw PHP (based on this implementation: https://github.com/nothings/stb/blob/master/stb_perlin.h)

If you want to use the previous upgrade implementation of https://github.com/A1essandro/perlin-noise-generator you can install the `0.0.1` version

## Requirements
This package is only supported on PHP 8.4 and above.

## Installing

### Composer
See more [getcomposer.org](http://getcomposer.org).

Execute command 
```
composer require cryde/noise-functions
```

## Usage

You can check `demo` folder.

```php
$size = 1024;
$noise = new Noise();
$map = $noise->perlin2DArray(
    width: $size,
    height: $size,
    scale: 4.0,
    seed: 42
);
```
