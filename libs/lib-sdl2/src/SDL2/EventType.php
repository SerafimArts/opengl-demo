<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface EventType
{
    public const SDL_FIRSTEVENT               = 0;
    public const SDL_QUIT                     = 0x0100;
    public const SDL_APP_TERMINATING          = 0x0101;
    public const SDL_APP_LOWMEMORY            = 0x0102;
    public const SDL_APP_WILLENTERBACKGROUND  = 0x0103;
    public const SDL_APP_DIDENTERBACKGROUND   = 0x0104;
    public const SDL_APP_WILLENTERFOREGROUND  = 0x0105;
    public const SDL_APP_DIDENTERFOREGROUND   = 0x0106;
    public const SDL_LOCALECHANGED            = 0x0107;
    public const SDL_DISPLAYEVENT             = 0x0150;
    public const SDL_WINDOWEVENT              = 0x0200;
    public const SDL_SYSWMEVENT               = 0x0201;
    public const SDL_KEYDOWN                  = 0x0300;
    public const SDL_KEYUP                    = 0x0301;
    public const SDL_TEXTEDITING              = 0x0302;
    public const SDL_TEXTINPUT                = 0x0303;
    public const SDL_KEYMAPCHANGED            = 0x0304;
    public const SDL_TEXTEDITING_EXT          = 0x0305;
    public const SDL_MOUSEMOTION              = 0x0400;
    public const SDL_MOUSEBUTTONDOWN          = 0x0401;
    public const SDL_MOUSEBUTTONUP            = 0x0402;
    public const SDL_MOUSEWHEEL               = 0x0403;
    public const SDL_JOYAXISMOTION            = 0x0600;
    public const SDL_JOYBALLMOTION            = 0x0601;
    public const SDL_JOYHATMOTION             = 0x0602;
    public const SDL_JOYBUTTONDOWN            = 0x0603;
    public const SDL_JOYBUTTONUP              = 0x0604;
    public const SDL_JOYDEVICEADDED           = 0x0605;
    public const SDL_JOYDEVICEREMOVED         = 0x0606;
    public const SDL_JOYBATTERYUPDATED        = 0x0607;
    public const SDL_CONTROLLERAXISMOTION     = 0x0650;
    public const SDL_CONTROLLERBUTTONDOWN     = 0x0651;
    public const SDL_CONTROLLERBUTTONUP       = 0x0652;
    public const SDL_CONTROLLERDEVICEADDED    = 0x0653;
    public const SDL_CONTROLLERDEVICEREMOVED  = 0x0654;
    public const SDL_CONTROLLERDEVICEREMAPPED = 0x0655;
    public const SDL_CONTROLLERTOUCHPADDOWN   = 0x0656;
    public const SDL_CONTROLLERTOUCHPADMOTION = 0x0657;
    public const SDL_CONTROLLERTOUCHPADUP     = 0x0658;
    public const SDL_CONTROLLERSENSORUPDATE   = 0x0659;
    public const SDL_FINGERDOWN               = 0x0700;
    public const SDL_FINGERUP                 = 0x0701;
    public const SDL_FINGERMOTION             = 0x0702;
    public const SDL_DOLLARGESTURE            = 0x0800;
    public const SDL_DOLLARRECORD             = 0x0801;
    public const SDL_MULTIGESTURE             = 0x0802;
    public const SDL_CLIPBOARDUPDATE          = 0x0900;
    public const SDL_DROPFILE                 = 0x1000;
    public const SDL_DROPTEXT                 = 0x1001;
    public const SDL_DROPBEGIN                = 0x1002;
    public const SDL_DROPCOMPLETE             = 0x1003;
    public const SDL_AUDIODEVICEADDED         = 0x1100;
    public const SDL_AUDIODEVICEREMOVED       = 0x1101;
    public const SDL_SENSORUPDATE             = 0x1200;
    public const SDL_RENDER_TARGETS_RESET     = 0x2000;
    public const SDL_RENDER_DEVICE_RESET      = 0x2001;
    public const SDL_POLLSENTINEL             = 0x7F00;
    public const SDL_USEREVENT                = 0x8000;
    public const SDL_LASTEVENT                = 0xFFFF;
}
