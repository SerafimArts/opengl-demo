<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface BitmapOrder
{
    public const SDL_BITMAPORDER_NONE = 0;
    public const SDL_BITMAPORDER_4321 = 1;
    public const SDL_BITMAPORDER_1234 = 2;
}
