<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface MessageBoxButtonFlags
{
    public const SDL_MESSAGEBOX_BUTTON_RETURNKEY_DEFAULT = 0x00000001;
    public const SDL_MESSAGEBOX_BUTTON_ESCAPEKEY_DEFAULT = 0x00000002;
}
