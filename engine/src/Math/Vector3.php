<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Vector3 extends Vector2
{
    /**
     * @var float
     */
    public float $z = 0;

    /**
     * @param float $x
     * @param float $y
     * @param float $z
     */
    public function __construct(float $x = 0, float $y = 0, float $z = 0)
    {
        parent::__construct($x, $y);

        $this->z = $z;
    }
}
