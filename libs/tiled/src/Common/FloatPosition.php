<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

abstract class FloatPosition
{
    /**
     * @param float $x
     * @param float $y
     */
    public function __construct(
        public float $x = 0,
        public float $y = 0,
    ) {
    }
}
