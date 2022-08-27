<?php

declare(strict_types=1);

namespace App;

use Bic\Lib\SDL2;
use Bic\UI\SDL2\Window;
use FFI\CData;

class Renderer
{
    /**
     * @var CData
     */
    public readonly CData $ptr;

    /**
     * @param SDL2 $sdl2
     * @param Window $window
     */
    public function __construct(
        private readonly SDL2 $sdl2,
        private readonly Window $window,
    ) {
        $flags = SDL2\RendererFlags::SDL_RENDERER_ACCELERATED
               | SDL2\RendererFlags::SDL_RENDERER_TARGETTEXTURE;

        $this->sdl2->SDL_SetHint(SDL2::SDL_HINT_RENDER_BATCHING, "1");
        $this->sdl2->SDL_SetHint(SDL2::SDL_HINT_RENDER_DRIVER, "opengl");
        $renderer = $this->sdl2->SDL_CreateRenderer($this->window->ptr, -1, $flags);

        if ($renderer === null) {
            throw new \RuntimeException($this->sdl2->getErrorMessage());
        }

        $this->ptr = $renderer;

        $this->sdl2->SDL_SetRenderDrawBlendMode($renderer, SDL2\BlendMode::SDL_BLENDMODE_BLEND);
        $this->sdl2->SDL_SetRenderDrawColor($this->ptr,0, 0, 0, 0);
    }

    /**
     * @return void
     */
    public function clean(): void
    {
        $this->sdl2->SDL_RenderClear($this->ptr);
    }

    /**
     * @return void
     */
    public function draw(): void
    {
        $this->sdl2->SDL_RenderPresent($this->ptr);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->sdl2->SDL_DestroyRenderer($this->ptr);
    }
}
