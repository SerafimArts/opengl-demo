<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface RendererFlags
{
    public const SDL_RENDERER_SOFTWARE      = 0x00000001;
    public const SDL_RENDERER_ACCELERATED   = 0x00000002;
    public const SDL_RENDERER_PRESENTVSYNC  = 0x00000004;
    public const SDL_RENDERER_TARGETTEXTURE = 0x00000008;
}
