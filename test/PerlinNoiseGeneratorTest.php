<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class PerlinNoiseGeneratorTest extends TestCase
{
    protected Noise\Noise $noise;

    protected function setUp(): void
    {
        $this->noise = new Noise\Noise();
    }


    public function testPerlin2DArray(): void
    {
        $size = 1024;
        $buffer = $this->noise->perlin2DArray($size, $size, 8.0);

        // sample first, last and some in the middle
        $this->assertEqualsWithDelta( 0.000, $buffer[0], 0.01);
        $this->assertEqualsWithDelta(-0.101, $buffer[100_000], 0.01);
        $this->assertEqualsWithDelta( 0.227, $buffer[200_000], 0.01);
        $this->assertEqualsWithDelta( 0.363, $buffer[300_000], 0.01);
        $this->assertEqualsWithDelta(-0.045, $buffer[400_000], 0.01);
        $this->assertEqualsWithDelta( 0.178, $buffer[500_000], 0.01);
        $this->assertEqualsWithDelta(-0.087, $buffer[600_000], 0.01);
        $this->assertEqualsWithDelta( 0.247, $buffer[700_000], 0.01);
        $this->assertEqualsWithDelta( 0.108, $buffer[800_000], 0.01);
        $this->assertEqualsWithDelta(-0.123, $buffer[900_000], 0.01);
        $this->assertEqualsWithDelta( 0.015, $buffer[$size * $size - 2], 0.01);
    }
}
