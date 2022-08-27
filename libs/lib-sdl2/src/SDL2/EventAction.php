<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface EventAction
{
    public const SDL_ADDEVENT  = 0;
    public const SDL_PEEKEVENT = 1;
    public const SDL_GETEVENT  = 2;
}
