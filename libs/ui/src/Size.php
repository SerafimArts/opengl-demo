<?php

declare(strict_types=1);

namespace Bic\UI;

/**
 * The width and height of the element, in screen coordinates.
 *
 * @property-read int $width
 * @property-read int $height
 */
final class Size
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
