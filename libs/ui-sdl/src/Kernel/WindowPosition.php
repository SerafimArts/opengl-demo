<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Kernel;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
interface WindowPosition
{
    public const SDL_WINDOWPOS_UNDEFINED_MASK = 0x1FFF0000;
    public const SDL_WINDOWPOS_UNDEFINED = self::SDL_WINDOWPOS_UNDEFINED_MASK | 0;
    public const SDL_WINDOWPOS_CENTERED_MASK = 0x2FFF0000;
    public const SDL_WINDOWPOS_CENTERED = self::SDL_WINDOWPOS_CENTERED_MASK | 0;
}
