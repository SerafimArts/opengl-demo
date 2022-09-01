<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

class Position
{
    /**
     * @param positive-int|0 $x
     * @param positive-int|0 $y
     */
    public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {
    }
}
