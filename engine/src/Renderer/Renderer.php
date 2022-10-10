<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Bic\UI\SDL\Window;
use Serafim\Bic\Native;
use Serafim\SDL\RendererPtr;
use Serafim\SDL\SDL;

/**
 * @method RendererPtr getPointer()
 */
class Renderer extends Native implements RendererInterface
{
    /**
     * @var int
     */
    private const DEFAULT_FLAGS = SDL::SDL_RENDERER_PRESENTVSYNC | SDL::SDL_RENDERER_ACCELERATED;

    /**
     * @var Driver
     */
    public Driver $driver;

    /**
     * @param Window $window
     * @param Driver|null $driver
     * @param int $flags
     */
    public function __construct(Window $window, Driver $driver = null, int $flags = self::DEFAULT_FLAGS)
    {
        parent::__construct();

        $this->driver = $driver ?? Driver::current();
        $cdata = $this->sdl->cast('SDL_Window*', $window->getCData());

        $this->ptr = $this->sdl->SDL_CreateRenderer($cdata, $this->driver->index, $flags);
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->sdl->SDL_RenderClear($this->ptr);
    }

    /**
     * @return void
     */
    public function present(): void
    {
        $this->sdl->SDL_RenderPresent($this->ptr);
    }
}
