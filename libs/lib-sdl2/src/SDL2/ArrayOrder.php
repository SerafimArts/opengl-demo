<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface ArrayOrder
{
    public const SDL_ARRAYORDER_NONE = 0;
    public const SDL_ARRAYORDER_RGB = 1;
    public const SDL_ARRAYORDER_RGBA = 2;
    public const SDL_ARRAYORDER_ARGB = 3;
    public const SDL_ARRAYORDER_BGR = 4;
    public const SDL_ARRAYORDER_BGRA = 5;
    public const SDL_ARRAYORDER_ABGR = 6;
}
