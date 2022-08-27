<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface LogPriority
{
    public const SDL_LOG_PRIORITY_VERBOSE  = 1;
    public const SDL_LOG_PRIORITY_DEBUG    = 2;
    public const SDL_LOG_PRIORITY_INFO     = 3;
    public const SDL_LOG_PRIORITY_WARN     = 4;
    public const SDL_LOG_PRIORITY_ERROR    = 5;
    public const SDL_LOG_PRIORITY_CRITICAL = 6;
    public const SDL_NUM_LOG_PRIORITIES    = 7;
}
