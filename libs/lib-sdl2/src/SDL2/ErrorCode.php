<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface ErrorCode
{
    public const SDL_ENOMEM = 0;
    public const SDL_EFREAD = 1;
    public const SDL_EFWRITE = 2;
    public const SDL_EFSEEK = 3;
    public const SDL_UNSUPPORTED = 4;
    public const SDL_LASTERROR = 5;
}
