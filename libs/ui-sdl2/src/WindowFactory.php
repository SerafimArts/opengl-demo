<?php

declare(strict_types=1);

namespace Bic\UI\SDL2;

use Bic\Lib\SDL2;
use Bic\Lib\SDL2\WindowFlags;
use Bic\UI\Window\Factory;

/**
 * @template-extends Factory<Window>
 *
 * @package ui-sdl2
 */
final class WindowFactory extends Factory
{
    /**
     * @psalm-var int-mask-of<WindowFlags::SDL_*>
     * @var int
     */
    private int $flags = WindowFlags::SDL_WINDOW_OPENGL;

    /**
     * @var positive-int|0
     */
    private int $displayId = 0;

    /**
     * @param SDL2 $sdl2
     * @param SDL2\Image $image
     * @psalm-param list<int-mask-of<WindowFlags::SDL_*>> $flags
     * @param array<int> $flags
     */
    public function __construct(
        private readonly SDL2 $sdl2,
        private readonly SDL2\Image $image,
        array $flags = [ WindowFlags::SDL_WINDOW_SHOWN ],
    ) {
        parent::__construct();

        foreach ($flags as $flag) {
            $this->flags |= $flag;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MixedArgument
     */
    public function instance(string $name, int $width, int $height, string $icon = null): Window
    {
        $pointer = $this->sdl2->SDL_CreateWindow(
            $name,
            SDL2::SDL_WINDOWPOS_CENTERED_MASK | $this->displayId,
            SDL2::SDL_WINDOWPOS_CENTERED_MASK | $this->displayId,
            $width,
            $height,
            ($this->flags ^ WindowFlags::SDL_WINDOW_SHOWN) | WindowFlags::SDL_WINDOW_HIDDEN,
        );

        if ($pointer === null) {
            throw new \LogicException($this->sdl2->getErrorMessage() ?: 'Unknown error');
        }

        $instance = new Window($this->sdl2, $this->image, $pointer, $this->detach(...));

        if ($icon !== null) {
            $instance->setIcon($icon);
        }

        if ($this->flags & WindowFlags::SDL_WINDOW_SHOWN) {
            $this->sdl2->SDL_ShowWindow($pointer);
        }

        return $instance;
    }
}
