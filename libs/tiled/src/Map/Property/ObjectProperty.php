<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<object>
 */
final class ObjectProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        object $value,
    ) {
        parent::__construct($name, $value);
    }
}
