<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Angle
{
    /**
     * @var float
     */
    public float $value;

    /**
     * @param float $angle
     */
    public function __construct(float $angle = 0.0)
    {
        $this->value = $angle;
    }
}
