<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface KeyMod
{
    public const KMOD_NONE     = 0x0000;
    public const KMOD_LSHIFT   = 0x0001;
    public const KMOD_RSHIFT   = 0x0002;
    public const KMOD_LCTRL    = 0x0040;
    public const KMOD_RCTRL    = 0x0080;
    public const KMOD_LALT     = 0x0100;
    public const KMOD_RALT     = 0x0200;
    public const KMOD_LGUI     = 0x0400;
    public const KMOD_RGUI     = 0x0800;
    public const KMOD_NUM      = 0x1000;
    public const KMOD_CAPS     = 0x2000;
    public const KMOD_MODE     = 0x4000;
    public const KMOD_SCROLL   = 0x8000;
    public const KMOD_CTRL     = self::KMOD_LCTRL | self::KMOD_RCTRL;
    public const KMOD_SHIFT    = self::KMOD_LSHIFT | self::KMOD_RSHIFT;
    public const KMOD_ALT      = self::KMOD_LALT | self::KMOD_RALT;
    public const KMOD_GUI      = self::KMOD_LGUI | self::KMOD_RGUI;
    public const KMOD_RESERVED = self::KMOD_SCROLL;
}
