<?php

declare(strict_types=1);

namespace Bic\Tiled\Map\Property;

use Bic\Tiled\Common\Color;

/**
 * @template-extends Property<Color>
 */
final class ColorProperty extends Property
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $name,
        Color $value,
    ) {
        parent::__construct($name, $value);
    }
}
