<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface GameControllerBindType
{
    public const SDL_CONTROLLER_BINDTYPE_NONE   = 0;
    public const SDL_CONTROLLER_BINDTYPE_BUTTON = 1;
    public const SDL_CONTROLLER_BINDTYPE_AXIS   = 2;
    public const SDL_CONTROLLER_BINDTYPE_HAT    = 3;
}
