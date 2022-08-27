<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface GameControllerType
{
    public const SDL_CONTROLLER_TYPE_UNKNOWN                      = 0;
    public const SDL_CONTROLLER_TYPE_XBOX360                      = 1;
    public const SDL_CONTROLLER_TYPE_XBOXONE                      = 2;
    public const SDL_CONTROLLER_TYPE_PS3                          = 3;
    public const SDL_CONTROLLER_TYPE_PS4                          = 4;
    public const SDL_CONTROLLER_TYPE_NINTENDO_SWITCH_PRO          = 5;
    public const SDL_CONTROLLER_TYPE_VIRTUAL                      = 6;
    public const SDL_CONTROLLER_TYPE_PS5                          = 7;
    public const SDL_CONTROLLER_TYPE_AMAZON_LUNA                  = 8;
    public const SDL_CONTROLLER_TYPE_GOOGLE_STADIA                = 9;
    public const SDL_CONTROLLER_TYPE_NVIDIA_SHIELD                = 10;
    public const SDL_CONTROLLER_TYPE_NINTENDO_SWITCH_JOYCON_LEFT  = 11;
    public const SDL_CONTROLLER_TYPE_NINTENDO_SWITCH_JOYCON_RIGHT = 12;
    public const SDL_CONTROLLER_TYPE_NINTENDO_SWITCH_JOYCON_PAIR  = 13;
}
