<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

use Bic\Tiled\Common\Position;
use Ds\Map as HashMap;

final class TileLayer extends Layer
{
    /**
     * @var HashMap<Tile, Position>
     */
    public readonly HashMap $tiles;

    /**
     * @param iterable<Tile, Position> $tiles
     */
    public function __construct(
        iterable $tiles = [],
    ) {
        $this->tiles = new HashMap($tiles);
    }
}
