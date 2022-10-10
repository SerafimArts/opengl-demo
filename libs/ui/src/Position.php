<?php

declare(strict_types=1);

namespace Bic\UI;

/**
 * The X (left) and Y (top) coordinates
 *
 * @property-read int $x
 * @property-read int $y
 */
final class Position
{
    public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {
    }
}
