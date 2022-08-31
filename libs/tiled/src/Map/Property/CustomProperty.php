<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<string>
 */
final class CustomProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        string $value,
        public string $type,
    ) {
        parent::__construct($name, $value);
    }
}
