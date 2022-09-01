<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

class Size
{
    /**
     * @param positive-int $width
     * @param positive-int $height
     */
    public function __construct(
        public int $width = 1,
        public int $height = 1,
    ) {
    }
}
