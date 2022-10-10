<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Kernel;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
interface WindowFlags
{
    public const SDL_WINDOW_FULLSCREEN         = 0x00000001;
    public const SDL_WINDOW_OPENGL             = 0x00000002;
    public const SDL_WINDOW_SHOWN              = 0x00000004;
    public const SDL_WINDOW_HIDDEN             = 0x00000008;
    public const SDL_WINDOW_BORDERLESS         = 0x00000010;
    public const SDL_WINDOW_RESIZABLE          = 0x00000020;
    public const SDL_WINDOW_MINIMIZED          = 0x00000040;
    public const SDL_WINDOW_MAXIMIZED          = 0x00000080;
    public const SDL_WINDOW_INPUT_GRABBED      = 0x00000100;
    public const SDL_WINDOW_INPUT_FOCUS        = 0x00000200;
    public const SDL_WINDOW_MOUSE_FOCUS        = 0x00000400;
    public const SDL_WINDOW_FULLSCREEN_DESKTOP = (self::SDL_WINDOW_FULLSCREEN | 0x00001000);
    public const SDL_WINDOW_FOREIGN            = 0x00000800;
    public const SDL_WINDOW_ALLOW_HIGHDPI      = 0x00002000;
    public const SDL_WINDOW_MOUSE_CAPTURE      = 0x00004000;
    public const SDL_WINDOW_ALWAYS_ON_TOP      = 0x00008000;
    public const SDL_WINDOW_SKIP_TASKBAR       = 0x00010000;
    public const SDL_WINDOW_UTILITY            = 0x00020000;
    public const SDL_WINDOW_TOOLTIP            = 0x00040000;
    public const SDL_WINDOW_POPUP_MENU         = 0x00080000;
    public const SDL_WINDOW_VULKAN             = 0x10000000;
}
