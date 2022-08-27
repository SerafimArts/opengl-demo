<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2\Image;

interface InitFlags
{
    public const IMG_INIT_JPG    = 0x00000001;
    public const IMG_INIT_PNG    = 0x00000002;
    public const IMG_INIT_TIF    = 0x00000004;
    public const IMG_INIT_WEBP   = 0x00000008;
    public const IMG_INIT_JXL    = 0x00000010;
    public const IMG_INIT_AVIF   = 0x00000020;
}
