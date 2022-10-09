<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Vector2 extends Vector
{
    /**
     * @var float
     */
    public float $y = 0;

    /**
     * @param float $x
     * @param float $y
     */
    public function __construct(float $x = 0, float $y = 0)
    {
        parent::__construct($x);

        $this->y = $y;
    }
}
