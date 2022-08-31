<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Map\PerspectiveMap;

final class HexagonalMap extends PerspectiveMap
{
    /**
     * Length of the side of a hex tile in pixels.
     *
     * @var int|null
     */
    public ?int $hexSideLength = null;
}
