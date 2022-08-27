<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface TouchDeviceType
{
    public const SDL_TOUCH_DEVICE_INVALID           = -1;
    public const SDL_TOUCH_DEVICE_DIRECT            = 0;
    public const SDL_TOUCH_DEVICE_INDIRECT_ABSOLUTE = 1;
    public const SDL_TOUCH_DEVICE_INDIRECT_RELATIVE = 2;
}
