<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard;

interface Modifier
{
    public const NONE     = 0x0000;
    public const LSHIFT   = 0x0001;
    public const RSHIFT   = 0x0002;
    public const LCTRL    = 0x0040;
    public const RCTRL    = 0x0080;
    public const LALT     = 0x0100;
    public const RALT     = 0x0200;
    public const LGUI     = 0x0400;
    public const RGUI     = 0x0800;
    public const NUM      = 0x1000;
    public const CAPS     = 0x2000;
    public const MODE     = 0x4000;
    public const SCROLL   = 0x8000;
    public const CTRL     = self::LCTRL | self::RCTRL;
    public const SHIFT    = self::LSHIFT | self::RSHIFT;
    public const ALT      = self::LALT | self::RALT;
    public const GUI      = self::LGUI | self::RGUI;
}
