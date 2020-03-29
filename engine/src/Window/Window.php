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
use Serafim\Bic\Renderer\Surface;
use Serafim\SDL\Kernel\Video\WindowFlags;
use Serafim\SDL\SDL;
use Serafim\SDL\WindowPtr;

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
     * @var SDL
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
        $this->sdl->setWindowIcon($this->window, $surface->getPointer());
    }

    /**
     * @return void
     */
    public function show(): void
    {
        $this->sdl->showWindow($this->window);
    }

    /**
     * @return void
     */
    public function hide(): void
    {
        $this->sdl->hideWindow($this->window);
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

        $sdl = SDL::getInstance();

        return new static($sdl->createWindow($title, $x, $y, $width, $height, $flags));
    }

    /**
     * @return CData|WindowPtr
     */
    public function getPointer(): CData
    {
        return $this->window;
    }
}
