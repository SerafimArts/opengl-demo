<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface BlendMode
{
    public const SDL_BLENDMODE_NONE    = 0x00000000;
    public const SDL_BLENDMODE_BLEND   = 0x00000001;
    public const SDL_BLENDMODE_ADD     = 0x00000002;
    public const SDL_BLENDMODE_MOD     = 0x00000004;
    public const SDL_BLENDMODE_MUL     = 0x00000008;
    public const SDL_BLENDMODE_INVALID = 0x7FFFFFFF;
}
