<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Serafim\Bic\Native;
use Serafim\Bic\Window\WindowInterface;
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
     * Renderer constructor.
     *
     * @param WindowInterface $window
     * @param Driver|null $driver
     * @param int $flags
     */
    public function __construct(WindowInterface $window, Driver $driver = null, int $flags = self::DEFAULT_FLAGS)
    {
        parent::__construct();

        $this->driver = $driver ?? Driver::current();

        $this->ptr = $this->sdl->SDL_CreateRenderer($window->getPointer(), $this->driver->index, $flags);
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
