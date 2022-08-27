<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface MessageBoxColorType
{
    public const SDL_MESSAGEBOX_COLOR_BACKGROUND        = 0;
    public const SDL_MESSAGEBOX_COLOR_TEXT              = 1;
    public const SDL_MESSAGEBOX_COLOR_BUTTON_BORDER     = 2;
    public const SDL_MESSAGEBOX_COLOR_BUTTON_BACKGROUND = 3;
    public const SDL_MESSAGEBOX_COLOR_BUTTON_SELECTED   = 4;
    public const SDL_MESSAGEBOX_COLOR_MAX               = 5;
}
