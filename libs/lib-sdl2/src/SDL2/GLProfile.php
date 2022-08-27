<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface GLProfile
{
    public const SDL_GL_CONTEXT_PROFILE_CORE           = 0x0001;
    public const SDL_GL_CONTEXT_PROFILE_COMPATIBILITY  = 0x0002;
    public const SDL_GL_CONTEXT_PROFILE_ES             = 0x0004;
}
