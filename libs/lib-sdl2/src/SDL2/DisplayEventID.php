<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface DisplayEventID
{
    public const SDL_DISPLAYEVENT_NONE = 0;
    public const SDL_DISPLAYEVENT_ORIENTATION = 1;
    public const SDL_DISPLAYEVENT_CONNECTED = 2;
    public const SDL_DISPLAYEVENT_DISCONNECTED = 3;
}
