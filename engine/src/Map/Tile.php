<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Map;

use FFI\CData;
use Serafim\Bic\Renderer\Texture;
use Serafim\SDL\RectPtr;

/**
 * Class Tile
 */
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
     * Tile constructor.
     *
     * @param Texture $texture
     */
    public function __construct(Texture $texture)
    {
        $this->texture = $texture;
    }
}
