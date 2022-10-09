<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer\Viewport;

use FFI\CData;
use Serafim\SDL\RectPtr;

class StretchViewport extends Viewport
{
    /**
     * @param float $x
     * @return float
     */
    public function x(float $x): float
    {
        return $this->source->x / $this->target->x * $x;
    }

    /**
     * @param float $y
     * @return float
     */
    public function y(float $y): float
    {
        return $this->source->y / $this->target->y * $y;
    }

    /**
     * @param CData|RectPtr $rect
     * @return CData|RectPtr
     */
    public function transform(CData $rect): CData
    {
        $result = $this->rect($rect);

        $result->x = $this->source->x / $this->target->x * $rect->x;
        $result->y = $this->source->y / $this->target->y * $rect->y;
        $result->w = $this->source->x / $this->target->x * $rect->w;
        $result->h = $this->source->y / $this->target->y * $rect->h;

        return $result;
    }
}
