<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface JoystickType
{
    public const SDL_JOYSTICK_TYPE_UNKNOWN        = 0;
    public const SDL_JOYSTICK_TYPE_GAMECONTROLLER = 1;
    public const SDL_JOYSTICK_TYPE_WHEEL          = 2;
    public const SDL_JOYSTICK_TYPE_ARCADE_STICK   = 3;
    public const SDL_JOYSTICK_TYPE_FLIGHT_STICK   = 4;
    public const SDL_JOYSTICK_TYPE_DANCE_PAD      = 5;
    public const SDL_JOYSTICK_TYPE_GUITAR         = 6;
    public const SDL_JOYSTICK_TYPE_DRUM_KIT       = 7;
    public const SDL_JOYSTICK_TYPE_ARCADE_PAD     = 8;
    public const SDL_JOYSTICK_TYPE_THROTTLE       = 9;
}
