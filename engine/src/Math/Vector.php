<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Vector
{
    /**
     * @var float
     */
    public float $x = 0;

    /**
     * @param float $x
     */
    public function __construct(float $x = 0)
    {
        $this->x = $x;
    }
}
