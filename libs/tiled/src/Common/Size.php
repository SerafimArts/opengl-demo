<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

abstract class Size
{
    /**
     * @psalm-param positive-int|0 $width
     * @psalm-param positive-int|0 $height
     */
    public function __construct(
        public int $width = 0,
        public int $height = 0,
    ) {
    }
}
