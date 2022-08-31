<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template TType of mixed
 */
abstract class Property
{
    /**
     * @param non-empty-string $name
     * @param TType $value
     */
    public function __construct(
        public string $name,
        public mixed $value,
    ) {
    }
}
