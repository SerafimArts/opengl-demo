<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface PackedOrder
{
    public const SDL_PACKEDORDER_NONE = 0;
    public const SDL_PACKEDORDER_XRGB = 1;
    public const SDL_PACKEDORDER_RGBX = 2;
    public const SDL_PACKEDORDER_ARGB = 3;
    public const SDL_PACKEDORDER_RGBA = 4;
    public const SDL_PACKEDORDER_XBGR = 5;
    public const SDL_PACKEDORDER_BGRX = 6;
    public const SDL_PACKEDORDER_ABGR = 7;
    public const SDL_PACKEDORDER_BGRA = 8;
}
