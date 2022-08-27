<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface HitTestResult
{
    public const SDL_HITTEST_NORMAL             = 0;
    public const SDL_HITTEST_DRAGGABLE          = 1;
    public const SDL_HITTEST_RESIZE_TOPLEFT     = 2;
    public const SDL_HITTEST_RESIZE_TOP         = 3;
    public const SDL_HITTEST_RESIZE_TOPRIGHT    = 4;
    public const SDL_HITTEST_RESIZE_RIGHT       = 5;
    public const SDL_HITTEST_RESIZE_BOTTOMRIGHT = 6;
    public const SDL_HITTEST_RESIZE_BOTTOM      = 7;
    public const SDL_HITTEST_RESIZE_BOTTOMLEFT  = 8;
    public const SDL_HITTEST_RESIZE_LEFT        = 9;
}
