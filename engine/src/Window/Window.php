<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Window;

use FFI\CData;
use SDL\SDLNativeApiAutocomplete;
use Serafim\Bic\Renderer\Surface;
use SDL\Kernel\Video\WindowFlags;
use SDL\SDL;
use SDL\WindowPtr;

/**
 * Class Window
 */
class Window implements WindowInterface
{
    /**
     * @var int
     */
    private const DEFAULT_FLAGS = WindowFlags::SDL_WINDOW_HIDDEN | WindowFlags::SDL_WINDOW_OPENGL;

    /**
     * @var CData|WindowPtr
     */
    private CData $window;

    /**
     * @var SDL|SDLNativeApiAutocomplete
     */
    private SDL $sdl;

    /**
     * Window constructor.
     *
     * @param CData|WindowPtr $window
     */
    public function __construct(CData $window)
    {
        $this->window = $window;
        $this->sdl = SDL::getInstance();
    }

    /**
     * @param string $pathname
     * @return void
     */
    public function setIconFromPathname(string $pathname): void
    {
        $this->setIcon(Surface::fromPathname($pathname));
    }

    /**
     * @param Surface $surface
     * @return void
     */
    public function setIcon(Surface $surface): void
    {
        $this->sdl->SDL_SetWindowIcon($this->window, $surface->getPointer());
    }

    /**
     * @return void
     */
    public function show(): void
    {
        $this->sdl->SDL_ShowWindow($this->window);
    }

    /**
     * @return void
     */
    public function hide(): void
    {
        $this->sdl->SDL_HideWindow($this->window);
    }

    /**
     * @param string $title
     * @param int $width
     * @param int $height
     * @param int $flags
     * @return static
     */
    public static function create(string $title, int $width, int $height, int $flags = self::DEFAULT_FLAGS): self
    {
        $x = $y = SDL::SDL_WINDOWPOS_CENTERED;

        /** @var SDLNativeApiAutocomplete $sdl */
        $sdl = SDL::getInstance();

        return new static($sdl->SDL_CreateWindow($title, $x, $y, $width, $height, $flags));
    }

    /**
     * @return CData|WindowPtr
     */
    public function getPointer(): CData
    {
        return $this->window;
    }
}
