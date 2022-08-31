<?php

declare(strict_types=1);

namespace Bic\Tiled\Common;

abstract class IntPosition extends Position
{
    /**
     * @param int $x
     * @param int $y
     */
    public function __construct(
        int $x = 0,
        int $y = 0,
    ) {
        parent::__construct($x, $y);
    }
}
