<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer\Camera;

use FFI\CData;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Renderer\Viewport\ViewportInterface;

/**
 * Class OrthographicCamera
 */
class OrthographicCamera implements CameraInterface
{
    /**
     * @var Vector2
     */
    public Vector2 $position;

    /**
     * @var Vector2
     */
    public Vector2 $size;

    /**
     * @var ViewportInterface
     */
    private ViewportInterface $viewport;

    /**
     * Camera constructor.
     *
     * @param ViewportInterface $viewport
     */
    public function __construct(ViewportInterface $viewport)
    {
        $this->viewport = $viewport;
        $this->position = new Vector2();
        $this->size = new Vector2(1, 1);
    }

    /**
     * @param CData $rect
     * @return CData
     */
    public function transform(CData $rect): CData
    {
        $result = $this->transform($rect);

        $result->x = $rect->x - $this->viewport->x($this->position->x);
        $result->y = $rect->y - $this->viewport->y($this->position->y);
        $result->w = $rect->w * $this->size->x;
        $result->h = $rect->h * $this->size->y;

        return $result;
    }

    /**
     * @param float $x
     * @return float
     */
    public function x(float $x): float
    {
        return $x - $this->viewport->x($this->position->x);
    }

    /**
     * @param float $y
     * @return float
     */
    public function y(float $y): float
    {
        return $y - $this->viewport->y($this->position->y);
    }

    /**
     * @param float $w
     * @return float
     */
    public function w(float $w): float
    {
        return $w * $this->size->x;
    }

    /**
     * @param float $h
     * @return float
     */
    public function h(float $h): float
    {
        return $h * $this->size->y;
    }
}
