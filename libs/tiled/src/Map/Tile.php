<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

use Bic\Tiled\Common\Position;
use Bic\Tiled\Texture\TextureInterface;

final class Tile
{
    /**
     * @param TextureInterface $texture
     * @param Position $index
     */
    public function __construct(
        public readonly TextureInterface $texture,
        public readonly Position $index,
    ) {
    }
}
