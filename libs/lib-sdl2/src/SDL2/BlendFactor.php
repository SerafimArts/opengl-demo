<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface BlendFactor
{
    public const SDL_BLENDFACTOR_ZERO                = 0x01;
    public const SDL_BLENDFACTOR_ONE                 = 0x02;
    public const SDL_BLENDFACTOR_SRC_COLOR           = 0x03;
    public const SDL_BLENDFACTOR_ONE_MINUS_SRC_COLOR = 0x04;
    public const SDL_BLENDFACTOR_SRC_ALPHA           = 0x05;
    public const SDL_BLENDFACTOR_ONE_MINUS_SRC_ALPHA = 0x06;
    public const SDL_BLENDFACTOR_DST_COLOR           = 0x07;
    public const SDL_BLENDFACTOR_ONE_MINUS_DST_COLOR = 0x08;
    public const SDL_BLENDFACTOR_DST_ALPHA           = 0x09;
    public const SDL_BLENDFACTOR_ONE_MINUS_DST_ALPHA = 0x0A;
}
