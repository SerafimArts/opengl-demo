<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface PackedLayout
{
    public const SDL_PACKEDLAYOUT_NONE = 0;
    public const SDL_PACKEDLAYOUT_332 = 1;
    public const SDL_PACKEDLAYOUT_4444 = 2;
    public const SDL_PACKEDLAYOUT_1555 = 3;
    public const SDL_PACKEDLAYOUT_5551 = 4;
    public const SDL_PACKEDLAYOUT_565 = 5;
    public const SDL_PACKEDLAYOUT_8888 = 6;
    public const SDL_PACKEDLAYOUT_2101010 = 7;
    public const SDL_PACKEDLAYOUT_1010102 = 8;
}
