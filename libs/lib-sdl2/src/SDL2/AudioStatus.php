<?php

declare(strict_types=1);

namespace Bic\Lib\SDL2;

interface AudioStatus
{
    public const SDL_AUDIO_STOPPED = 0;
    public const SDL_AUDIO_PLAYING = 1;
    public const SDL_AUDIO_PAUSED = 2;
}
