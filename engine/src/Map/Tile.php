<?php

declare(strict_types=1);

namespace Serafim\Bic\Map;

use FFI\CData;
use Serafim\Bic\Renderer\Texture;
use Serafim\SDL\RectPtr;

class Tile
{
    /**
     * @var Texture
     */
    public Texture $texture;

    /**
     * @var CData|RectPtr
     */
    public CData $clip;

    /**
     * @var CData|RectPtr
     */
    public CData $dest;

    /**
     * @param Texture $texture
     */
    public function __construct(Texture $texture)
    {
        $this->texture = $texture;
    }
}
