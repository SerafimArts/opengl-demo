<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Kernel;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
interface MouseButton
{
    public const SDL_BUTTON_LEFT = 1;
    public const SDL_BUTTON_MIDDLE = 2;
    public const SDL_BUTTON_RIGHT = 3;
    public const SDL_BUTTON_X1 = 4;
    public const SDL_BUTTON_X2 = 5;
}
