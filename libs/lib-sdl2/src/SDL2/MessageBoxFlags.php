<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface MessageBoxFlags
{
    public const SDL_MESSAGEBOX_ERROR                 = 0x00000010;
    public const SDL_MESSAGEBOX_WARNING               = 0x00000020;
    public const SDL_MESSAGEBOX_INFORMATION           = 0x00000040;
    public const SDL_MESSAGEBOX_BUTTONS_LEFT_TO_RIGHT = 0x00000080;
    public const SDL_MESSAGEBOX_BUTTONS_RIGHT_TO_LEFT = 0x00000100;
}
