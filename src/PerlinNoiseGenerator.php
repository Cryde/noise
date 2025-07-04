<?php

namespace MapGenerator;

use LogicException;
use SplFixedArray;

class PerlinNoiseGenerator
{
    protected SplFixedArray $terra;
    public float $persistence {
        get {
            return $this->persistence;
        }
        set {
            $this->persistence = $value;
        }
    }
    public int $size {
        get {
            return $this->size;
        }
        set {
            $this->size = $value;
        }
    }
    const string SIZE = 'size';
    const string PERSISTENCE = 'persistence';
    const string MAP_SEED = 'map_seed';

    protected int|float|string $mapSeed;

    protected float|int $numericMapSeed;

    public function getMapSeed(): float|int|string
    {
        return $this->mapSeed;
    }

    public function setMapSeed(int|float|string $mapSeed): void
    {
        $this->mapSeed = $mapSeed;

        $this->numericMapSeed = is_numeric($mapSeed) ? $mapSeed : intval(substr(md5($mapSeed), -8), 16);
    }

    public function generate(array $options = []): SplFixedArray
    {
        $this->setOptions($options);
        $this->initTerra();
        for ($k = 0; $k < $this->getOctaves(); $k++) {
            $this->octave($k);
        }

        return $this->terra;
    }

    public function setOptions(array $options): void
    {
        if (array_key_exists(static::MAP_SEED, $options)) {
            $this->setMapSeed($options[static::MAP_SEED]);
        }

        if (array_key_exists(static::SIZE, $options)) {
            $this->size = $options[static::SIZE];
        }

        if (array_key_exists(static::PERSISTENCE, $options)) {
            $this->persistence = $options[static::PERSISTENCE];
        }
    }

    protected function octave(int $octave): void
    {
        $freq = pow(2, $octave);
        $amp = pow($this->persistence, $octave);
        $n = $m = $freq + 1;
        $arr = [];
        for ($j = 0; $j < $m; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $arr[$j][$i] = $this->random() * $amp;
            }
        }
        $nx = $this->size / ($n - 1);
        $ny = $this->size / ($m - 1);
        for ($ky = 0; $ky < $this->size; $ky++) {
            for ($kx = 0; $kx < $this->size; $kx++) {
                $i = (int)($kx / $nx);
                $j = (int)($ky / $ny);
                $dx0 = $kx - $i * $nx;
                $dx1 = $nx - $dx0;
                $dy0 = $ky - $j * $ny;
                $dy1 = $ny - $dy0;
                $z = ($arr[$j][$i] * $dx1 * $dy1
                        + $arr[$j][$i + 1] * $dx0 * $dy1
                        + $arr[$j + 1][$i] * $dx1 * $dy0
                        + $arr[$j + 1][$i + 1] * $dx0 * $dy0)
                    / ($nx * $ny);
                $this->terra[$ky][$kx] += $z;
            }
        }
    }

    /**
     * terra array initialization
     */
    protected function initTerra(): void
    {
        if (empty($this->mapSeed)) {
            $this->setMapSeed(microtime(true));
        }

        if (!$this->persistence) {
            throw new LogicException('Persistence must be set');
        }

        if (!$this->size) {
            throw new LogicException('Size must be set');
        }

        mt_srand((int)($this->numericMapSeed * $this->persistence * $this->size));
        $this->terra = new SplFixedArray($this->size);
        for ($y = 0; $y < $this->size; $y++) {
            $this->terra[$y] = new SplFixedArray($this->size);
            for ($x = 0; $x < $this->size; $x++) {
                $this->terra[$y][$x] = 0;
            }
        }
    }

    protected function random(): float|int
    {
        return mt_rand() / getrandmax();
    }

    protected function getOctaves(): int
    {
        return (int)log($this->size, 2);
    }
}