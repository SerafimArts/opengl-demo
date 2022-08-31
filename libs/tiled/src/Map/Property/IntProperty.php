<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<int>
 */
final class IntProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        int $value,
    ) {
        parent::__construct($name, $value);
    }
}
