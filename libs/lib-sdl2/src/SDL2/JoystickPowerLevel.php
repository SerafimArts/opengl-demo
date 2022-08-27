<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface JoystickPowerLevel
{
    public const SDL_JOYSTICK_POWER_UNKNOWN = -1;
    public const SDL_JOYSTICK_POWER_EMPTY   = 0;
    public const SDL_JOYSTICK_POWER_LOW     = 1;
    public const SDL_JOYSTICK_POWER_MEDIUM  = 2;
    public const SDL_JOYSTICK_POWER_FULL    = 3;
    public const SDL_JOYSTICK_POWER_WIRED   = 4;
    public const SDL_JOYSTICK_POWER_MAX     = 5;
}
