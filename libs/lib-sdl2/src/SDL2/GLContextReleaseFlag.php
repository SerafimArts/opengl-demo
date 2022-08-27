<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface GLContextReleaseFlag
{
    public const SDL_GL_CONTEXT_RELEASE_BEHAVIOR_NONE   = 0x0000;
    public const SDL_GL_CONTEXT_RELEASE_BEHAVIOR_FLUSH  = 0x0001;
}
