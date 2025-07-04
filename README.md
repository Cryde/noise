# perlin-noise-generator

## Description
Heightmaps generator on PHP using perlin-noise algorithm.

This package is just an upgrade of https://github.com/A1essandro/perlin-noise-generator to support higher PHP version.  
In the future it might contain decoupled function 

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

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->size = 100; //heightmap size: 100x100
$generator->persistence = 0.8; //map roughness
$generator->setMapSeed('value'); //optional
$map = $generator->generate();
```

#### or

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$map = $generator->generate([
    PerlinNoiseGenerator::SIZE => 100,
    PerlinNoiseGenerator::PERSISTENCE => 0.8,
    PerlinNoiseGenerator::MAP_SEED => 'value'
]);
```

#### mixed:

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->size = 100;
$map = $generator->generate([
    PerlinNoiseGenerator::PERSISTENCE => 0.8
]);
```
