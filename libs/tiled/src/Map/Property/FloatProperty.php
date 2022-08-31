<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<float>
 */
final class FloatProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        float $value,
    ) {
        parent::__construct($name, $value);
    }
}
