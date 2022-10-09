<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Opacity
{
    /**
     * @var float
     */
    public float $value;

    /**
     * @param float $value
     */
    public function __construct(float $value = 255.0)
    {
        $this->value = $value;
    }
}
