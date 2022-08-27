<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface DisplayOrientation
{
    public const SDL_ORIENTATION_UNKNOWN = 0;
    public const SDL_ORIENTATION_LANDSCAPE = 1;
    public const SDL_ORIENTATION_LANDSCAPE_FLIPPED = 2;
    public const SDL_ORIENTATION_PORTRAIT = 3;
    public const SDL_ORIENTATION_PORTRAIT_FLIPPED = 4;
}
