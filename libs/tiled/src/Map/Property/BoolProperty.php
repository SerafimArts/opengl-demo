<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<bool>
 */
final class BoolProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        bool $value,
    ) {
        parent::__construct($name, $value);
    }
}
