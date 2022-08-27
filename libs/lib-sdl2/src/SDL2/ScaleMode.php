<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface ScaleMode
{
    /**
     * @deprecated please use {@see SDL_SCALE_MODE_NEAREST} instead.
     */
    public const SDL_ScaleModeNearest = 0;

    /**
     * @deprecated please use {@see SDL_SCALE_MODE_LINEAR} instead.
     */
    public const SDL_ScaleModeLinear = 1;

    /**
     * @deprecated please use {@see SDL_SCALE_MODE_BEST} instead.
     */
    public const SDL_ScaleModeBest = 2;

    public const SDL_SCALE_MODE_NEAREST = self::SDL_ScaleModeNearest;
    public const SDL_SCALE_MODE_LINEAR = self::SDL_ScaleModeLinear;
    public const SDL_SCALE_MODE_BEST = self::SDL_ScaleModeBest;
}
