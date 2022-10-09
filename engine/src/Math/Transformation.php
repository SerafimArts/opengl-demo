<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

use FFI\CData;
use Serafim\Bic\Util;
use Serafim\SDL\Rect;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;

class Transformation
{
    /**
     * @var CData|Rect
     */
    public CData $source;

    /**
     * @var CData|Rect
     */
    public CData $target;

    /**
     * @var CData|RectPtr
     */
    private CData $memory;

    /**
     * @param CData $source
     * @param CData $target
     */
    public function __construct(CData $source, CData $target)
    {
        $this->source = $source;
        $this->target = $target;
        $this->memory = SDL::addr(Util::createRect());
    }

    /**
     * @param CData|Rect $rect
     * @return CData|Rect
     */
    public function transform(CData $rect): CData
    {
        $this->memory->x = (int)($this->source->x / $this->target->x * $rect->x);
        $this->memory->y = (int)($this->source->y / $this->target->y * $rect->y);
        $this->memory->w = (int)($this->source->w / $this->target->w * $rect->w);
        $this->memory->h = (int)($this->source->h / $this->target->h * $rect->h);

        return $this->memory;
    }
}
