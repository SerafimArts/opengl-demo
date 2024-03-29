<?php

declare(strict_types=1);

namespace Bic\Image\SDL\Kernel;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Bic\Image\SDL
 */
interface PixelFormat
{
    public const SDL_PIXELFORMAT_UNKNOWN = 0;
    public const SDL_PIXELFORMAT_INDEX1LSB = 286261504;
    public const SDL_PIXELFORMAT_INDEX1MSB = 287310080;
    public const SDL_PIXELFORMAT_INDEX4LSB = 303039488;
    public const SDL_PIXELFORMAT_INDEX4MSB = 304088064;
    public const SDL_PIXELFORMAT_INDEX8 = 318769153;
    public const SDL_PIXELFORMAT_RGB332 = 336660481;
    public const SDL_PIXELFORMAT_RGB444 = 353504258;
    public const SDL_PIXELFORMAT_RGB555 = 353570562;
    public const SDL_PIXELFORMAT_BGR555 = 357764866;
    public const SDL_PIXELFORMAT_ARGB4444 = 355602434;
    public const SDL_PIXELFORMAT_RGBA4444 = 356651010;
    public const SDL_PIXELFORMAT_ABGR4444 = 359796738;
    public const SDL_PIXELFORMAT_BGRA4444 = 360845314;
    public const SDL_PIXELFORMAT_ARGB1555 = 355667970;
    public const SDL_PIXELFORMAT_RGBA5551 = 356782082;
    public const SDL_PIXELFORMAT_ABGR1555 = 359862274;
    public const SDL_PIXELFORMAT_BGRA5551 = 360976386;
    public const SDL_PIXELFORMAT_RGB565 = 353701890;
    public const SDL_PIXELFORMAT_BGR565 = 357896194;
    public const SDL_PIXELFORMAT_RGB24 = 386930691;
    public const SDL_PIXELFORMAT_BGR24 = 390076419;
    public const SDL_PIXELFORMAT_RGB888 = 370546692;
    public const SDL_PIXELFORMAT_RGBX8888 = 371595268;
    public const SDL_PIXELFORMAT_BGR888 = 374740996;
    public const SDL_PIXELFORMAT_BGRX8888 = 375789572;
    public const SDL_PIXELFORMAT_ARGB8888 = 372645892;
    public const SDL_PIXELFORMAT_RGBA8888 = 373694468;
    public const SDL_PIXELFORMAT_ABGR8888 = 376840196;
    public const SDL_PIXELFORMAT_BGRA8888 = 377888772;
    public const SDL_PIXELFORMAT_ARGB2101010 = 372711428;
    public const SDL_PIXELFORMAT_YV12 = 842094169;
    public const SDL_PIXELFORMAT_IYUV = 1448433993;
    public const SDL_PIXELFORMAT_YUY2 = 844715353;
    public const SDL_PIXELFORMAT_UYVY = 1498831189;
    public const SDL_PIXELFORMAT_YVYU = 1431918169;
    public const SDL_PIXELFORMAT_NV12 = 842094158;
    public const SDL_PIXELFORMAT_NV21 = 825382478;
    public const SDL_PIXELFORMAT_EXTERNAL_OES = 542328143;
}
