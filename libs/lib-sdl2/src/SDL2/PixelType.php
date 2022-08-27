<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface PixelType
{
    public const SDL_PIXELTYPE_UNKNOWN = 0;
    public const SDL_PIXELTYPE_INDEX1 = 1;
    public const SDL_PIXELTYPE_INDEX4 = 2;
    public const SDL_PIXELTYPE_INDEX8 = 3;
    public const SDL_PIXELTYPE_PACKED8 = 4;
    public const SDL_PIXELTYPE_PACKED16 = 5;
    public const SDL_PIXELTYPE_PACKED32 = 6;
    public const SDL_PIXELTYPE_ARRAYU8 = 7;
    public const SDL_PIXELTYPE_ARRAYU16 = 8;
    public const SDL_PIXELTYPE_ARRAYU32 = 9;
    public const SDL_PIXELTYPE_ARRAYF16 = 10;
    public const SDL_PIXELTYPE_ARRAYF32 = 11;
}
