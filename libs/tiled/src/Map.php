<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Common\Size;
use Bic\Tiled\Map\Layer;
use Ds\Vector;

final class Map
{
    /**
     * @var Vector<Layer>
     */
    public readonly Vector $layers;

    /**
     * @param Size $size
     * @param Size $tiles
     * @param iterable<Layer> $layers
     */
    public function __construct(
        public readonly Size $size = new Size(256, 256),
        public readonly Size $tiles = new Size(32, 32),
        iterable $layers = [],
    ) {
        $this->layers = new Vector($layers);
    }
}
