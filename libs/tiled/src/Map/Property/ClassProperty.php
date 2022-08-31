<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

/**
 * @template-extends Property<string>
 */
final class ClassProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        string $value,
    ) {
        parent::__construct($name, $value);
    }
}
