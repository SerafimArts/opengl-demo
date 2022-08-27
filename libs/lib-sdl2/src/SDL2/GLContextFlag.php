<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface GLContextFlag
{
    public const SDL_GL_CONTEXT_DEBUG_FLAG              = 0x0001;
    public const SDL_GL_CONTEXT_FORWARD_COMPATIBLE_FLAG = 0x0002;
    public const SDL_GL_CONTEXT_ROBUST_ACCESS_FLAG      = 0x0004;
    public const SDL_GL_CONTEXT_RESET_ISOLATION_FLAG    = 0x0008;
}
