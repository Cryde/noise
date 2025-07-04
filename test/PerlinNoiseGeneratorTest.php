<?php

use MapGenerator\PerlinNoiseGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';


class PerlinNoiseGeneratorTest extends TestCase
{
    protected PerlinNoiseGenerator $perlinNoiseGenerator;

    protected function setUp(): void
    {
        $this->perlinNoiseGenerator = new PerlinNoiseGenerator();
    }

    public static function providerSetSize(): array
    {
        return [
            [2],
            [10],
            [100],
        ];
    }

    public static function providerSetSizeNotInt(): array
    {
        return [
            ['a'],
            [2.1],
            [10.],
        ];
    }

    public static function providerSetInvalidPersistence(): array
    {
        return [
            ['a'],
            [null],
            [[]],
        ];
    }

    public static function providerSetInvalidMapSeed(): array
    {
        return [
            [[]],
            [null],
            [new StdClass()],
        ];
    }

    #[DataProvider('providerSetSize')]
    public function testSize($count)
    {
        $this->perlinNoiseGenerator->persistence = 0.5;
        $this->perlinNoiseGenerator->size = $count;
        $map = $this->perlinNoiseGenerator->generate();

        $this->assertCount($count, $map);
        $this->assertEquals($this->perlinNoiseGenerator->size, count($map));
        $this->assertEquals(pow($count, 2), count(self::expandMap($map)));
    }

    #[DataProvider('providerSetInvalidPersistence')]
    public function testSetSizeNotInt($sizeToSet)
    {
        $this->expectException(TypeError::class);
        $this->perlinNoiseGenerator = $sizeToSet;
    }

    #[DataProvider('providerSetInvalidMapSeed')]
    public function testSetInvalidMapSeed($seed)
    {
        $this->expectException(TypeError::class);
        $this->perlinNoiseGenerator->setMapSeed($seed);
    }

    #[DataProvider('providerSetInvalidPersistence')]
    public function testSetInvalidPersistence($persistence)
    {
        $this->expectException(TypeError::class);
        $this->perlinNoiseGenerator = $persistence;
    }

    public function testContains()
    {
        $this->perlinNoiseGenerator->size = 10;
        $this->perlinNoiseGenerator->persistence = 0.5;
        $map = $this->perlinNoiseGenerator->generate();
        $points = [];
        foreach ($map as $line) {
            foreach ($line as $point) {
                $points[] = $point;
            }
        }
        $this->assertContainsOnlyFloat($points);
    }

    public function testMapSeed()
    {
        $mapHash1 = uniqid() . '1';
        $mapHash2 = uniqid() . '2';

        $this->perlinNoiseGenerator->size = 30;
        $this->perlinNoiseGenerator->persistence = 0.77;

        $this->perlinNoiseGenerator->setMapSeed($mapHash1);
        $map1 = $this->perlinNoiseGenerator->generate();
        $this->perlinNoiseGenerator->setMapSeed($mapHash2);
        $map2 = $this->perlinNoiseGenerator->generate();

        $this->assertNotEquals(self::expandMap($map1), self::expandMap($map2));

        $mapSeed = uniqid();
        $this->perlinNoiseGenerator->setMapSeed($mapSeed);
        $map1 = $this->perlinNoiseGenerator->generate();
        $this->perlinNoiseGenerator->setMapSeed($mapSeed);
        $map2 = $this->perlinNoiseGenerator->generate();

        $this->assertEquals($mapSeed, $this->perlinNoiseGenerator->getMapSeed());
        $this->assertEquals(self::expandMap($map1), self::expandMap($map2));
    }

    public function testGenerationWithoutPersistence()
    {
        $this->expectException(Error::class);
        $this->perlinNoiseGenerator->size = 30;
        $this->perlinNoiseGenerator->generate();
    }

    public function testGenerationWithoutSize()
    {
        $this->expectException(Error::class);
        $this->perlinNoiseGenerator->persistence = 0.5;
        $this->perlinNoiseGenerator->generate();
    }

    public function testGenerationWithOptions()
    {
        $this->assertNotEmpty($this->perlinNoiseGenerator->generate([
            PerlinNoiseGenerator::SIZE        => 100,
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED    => microtime(),
        ]));
    }

    public function testMixedOptionsGeneration()
    {
        $this->perlinNoiseGenerator->size = 100;
        $this->assertNotEmpty($this->perlinNoiseGenerator->generate([
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED    => microtime(),
        ]));
    }

    public function testGenerationViaOptionsWithoutSize()
    {
        $this->expectException(Error::class);
        $this->perlinNoiseGenerator->generate([
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED    => microtime(),
        ]);
    }

    #endregion
    private static function expandMap($map): array
    {
        $expandPoints = [];
        foreach ($map as $line) {
            foreach ($line as $point) {
                $expandPoints[] = $point;
            }
        }

        return $expandPoints;
    }
}
