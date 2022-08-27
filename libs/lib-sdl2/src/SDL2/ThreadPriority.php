<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface ThreadPriority
{
    public const SDL_THREAD_PRIORITY_LOW = 0;
    public const SDL_THREAD_PRIORITY_NORMAL = 1;
    public const SDL_THREAD_PRIORITY_HIGH = 2;
    public const SDL_THREAD_PRIORITY_TIME_CRITICAL = 3;
}
