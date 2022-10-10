<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Kernel;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
interface EventType
{
    public const SDL_FIRSTEVENT              = 0;
    public const SDL_QUIT                    = 0x100;
    public const SDL_APP_TERMINATING         = self::SDL_QUIT + 1;
    public const SDL_APP_LOWMEMORY           = self::SDL_QUIT + 2;
    public const SDL_APP_WILLENTERBACKGROUND = self::SDL_QUIT + 3;
    public const SDL_APP_DIDENTERBACKGROUND  = self::SDL_QUIT + 4;
    public const SDL_APP_WILLENTERFOREGROUND = self::SDL_QUIT + 5;
    public const SDL_APP_DIDENTERFOREGROUND  = self::SDL_QUIT + 6;
    /**
     * @since SDL 2.0.9
     */
    public const SDL_DISPLAYEVENT             = 0x150;
    public const SDL_WINDOWEVENT              = 0x200;
    public const SDL_SYSWMEVENT               = self::SDL_WINDOWEVENT + 1;
    public const SDL_KEYDOWN                  = 0x300;
    public const SDL_KEYUP                    = self::SDL_KEYDOWN + 1;
    public const SDL_TEXTEDITING              = self::SDL_KEYDOWN + 2;
    public const SDL_TEXTINPUT                = self::SDL_KEYDOWN + 3;
    public const SDL_KEYMAPCHANGED            = self::SDL_KEYDOWN + 4;
    public const SDL_MOUSEMOTION              = 0x400;
    public const SDL_MOUSEBUTTONDOWN          = self::SDL_MOUSEMOTION + 1;
    public const SDL_MOUSEBUTTONUP            = self::SDL_MOUSEMOTION + 2;
    public const SDL_MOUSEWHEEL               = self::SDL_MOUSEMOTION + 3;
    public const SDL_JOYAXISMOTION            = 0x600;
    public const SDL_JOYBALLMOTION            = self::SDL_JOYAXISMOTION + 1;
    public const SDL_JOYHATMOTION             = self::SDL_JOYAXISMOTION + 2;
    public const SDL_JOYBUTTONDOWN            = self::SDL_JOYAXISMOTION + 3;
    public const SDL_JOYBUTTONUP              = self::SDL_JOYAXISMOTION + 4;
    public const SDL_JOYDEVICEADDED           = self::SDL_JOYAXISMOTION + 5;
    public const SDL_JOYDEVICEREMOVED         = self::SDL_JOYAXISMOTION + 6;
    public const SDL_CONTROLLERAXISMOTION     = 0x650;
    public const SDL_CONTROLLERBUTTONDOWN     = self::SDL_CONTROLLERAXISMOTION + 1;
    public const SDL_CONTROLLERBUTTONUP       = self::SDL_CONTROLLERAXISMOTION + 2;
    public const SDL_CONTROLLERDEVICEADDED    = self::SDL_CONTROLLERAXISMOTION + 3;
    public const SDL_CONTROLLERDEVICEREMOVED  = self::SDL_CONTROLLERAXISMOTION + 4;
    public const SDL_CONTROLLERDEVICEREMAPPED = self::SDL_CONTROLLERAXISMOTION + 5;
    public const SDL_FINGERDOWN               = 0x700;
    public const SDL_FINGERUP                 = self::SDL_FINGERDOWN + 1;
    public const SDL_FINGERMOTION             = self::SDL_FINGERDOWN + 2;
    public const SDL_DOLLARGESTURE            = 0x800;
    public const SDL_DOLLARRECORD             = self::SDL_DOLLARGESTURE + 1;
    public const SDL_MULTIGESTURE             = self::SDL_DOLLARGESTURE + 2;
    public const SDL_CLIPBOARDUPDATE          = 0x900;
    public const SDL_DROPFILE                 = 0x1000;
    public const SDL_DROPTEXT                 = self::SDL_DROPFILE + 1;
    public const SDL_DROPBEGIN                = self::SDL_DROPFILE + 2;
    public const SDL_DROPCOMPLETE             = self::SDL_DROPFILE + 3;
    public const SDL_AUDIODEVICEADDED         = 0x1100;
    public const SDL_AUDIODEVICEREMOVED       = self::SDL_AUDIODEVICEADDED + 1;

    /**
     * @since SDL 2.0.9
     */
    public const SDL_SENSORUPDATE = 0x1200;
    /**
     * @since SDL 2.0.2
     */
    public const SDL_RENDER_TARGETS_RESET = 0x2000;
    public const SDL_RENDER_DEVICE_RESET  = self::SDL_RENDER_TARGETS_RESET + 1;
    public const SDL_USEREVENT            = 0x8000;
    public const SDL_LASTEVENT            = 0xFFFF;
}
