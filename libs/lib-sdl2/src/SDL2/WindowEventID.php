<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface WindowEventID
{
    public const SDL_WINDOWEVENT_NONE            = 0;
    public const SDL_WINDOWEVENT_SHOWN           = 1;
    public const SDL_WINDOWEVENT_HIDDEN          = 2;
    public const SDL_WINDOWEVENT_EXPOSED         = 3;
    public const SDL_WINDOWEVENT_MOVED           = 4;
    public const SDL_WINDOWEVENT_RESIZED         = 5;
    public const SDL_WINDOWEVENT_SIZE_CHANGED    = 6;
    public const SDL_WINDOWEVENT_MINIMIZED       = 7;
    public const SDL_WINDOWEVENT_MAXIMIZED       = 8;
    public const SDL_WINDOWEVENT_RESTORED        = 9;
    public const SDL_WINDOWEVENT_ENTER           = 10;
    public const SDL_WINDOWEVENT_LEAVE           = 11;
    public const SDL_WINDOWEVENT_FOCUS_GAINED    = 12;
    public const SDL_WINDOWEVENT_FOCUS_LOST      = 13;
    public const SDL_WINDOWEVENT_CLOSE           = 14;
    public const SDL_WINDOWEVENT_TAKE_FOCUS      = 15;
    public const SDL_WINDOWEVENT_HIT_TEST        = 16;
    public const SDL_WINDOWEVENT_ICCPROF_CHANGED = 17;
    public const SDL_WINDOWEVENT_DISPLAY_CHANGED = 18;
}
