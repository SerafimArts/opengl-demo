<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Map\Map;
use Bic\Tiled\Map\RenderOrder;

final class OrthogonalMap extends Map
{
    /**
     * @var RenderOrder
     */
    public RenderOrder $renderOrder = RenderOrder::RIGHT_DOWN;
}
