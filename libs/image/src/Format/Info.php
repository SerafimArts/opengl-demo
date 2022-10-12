<?php

declare(strict_types=1);

namespace Bic\Image\Format;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final class Info
{
    /**
     * @param positive-int $size
     */
    public function __construct(
        public readonly int $size = 1,
    ) {
    }
}
