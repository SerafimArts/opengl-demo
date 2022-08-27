<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface AssertState
{
    public const SDL_ASSERTION_RETRY = 0;
    public const SDL_ASSERTION_BREAK = 1;
    public const SDL_ASSERTION_ABORT = 2;
    public const SDL_ASSERTION_IGNORE = 3;
    public const SDL_ASSERTION_ALWAYS_IGNORE = 4;
}
