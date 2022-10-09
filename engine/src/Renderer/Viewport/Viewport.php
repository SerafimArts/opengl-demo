<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer\Viewport;

use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Renderer\TransformMemoizationTrait;
use Serafim\Bic\Window\Window;
use Serafim\SDL\SDL;

abstract class Viewport implements ViewportInterface
{
    use TransformMemoizationTrait;

    /**
     * @var Vector2
     */
    public Vector2 $target;

    /**
     * @var Vector2
     */
    public Vector2 $source;

    /**
     * @var SDL
     */
    private SDL $sdl;

    /**
     * @var Window
     */
    protected Window $window;

    /**
     * @param Window $window
     * @param Vector2|null $size
     */
    public function __construct(Window $window, Vector2 $size = null)
    {
        $this->sdl = SDL::getInstance();
        $this->window = $window;

        $this->source = new Vector2();
        $this->sync();

        $this->target = $size ?? clone $this->source;
    }

    /**
     * @param float $w
     * @return float
     */
    public function w(float $w): float
    {
        return $this->x($w);
    }

    /**
     * @param float $h
     * @return float
     */
    public function h(float $h): float
    {
        return $this->y($h);
    }

    /**
     * @return Vector2
     */
    public function getTarget(): Vector2
    {
        return $this->target;
    }

    /**
     * @return Vector2
     */
    public function getSource(): Vector2
    {
        return $this->source;
    }

    /**
     * @param int $width
     * @param int $height
     * @return void
     */
    public function target(int $width, int $height): void
    {
        $this->target->x = $width;
        $this->target->y = $height;
    }

    /**
     * @param int $width
     * @param int $height
     * @return void
     */
    public function source(int $width, int $height): void
    {
        $this->source->x = $width;
        $this->source->y = $height;
    }

    /**
     * @return void
     */
    public function sync(): void
    {
        $width = $this->sdl->new('int');
        $height = $this->sdl->new('int');

        $this->sdl->SDL_GetWindowSize(
            $this->window->getPointer(),
            SDL::addr($width),
            SDL::addr($height)
        );

        $this->source($width->cdata, $height->cdata);
    }

    /**
     * @param Vector2 $vec2
     * @return Vector2
     */
    public function get(Vector2 $vec2): Vector2
    {
        return new Vector2(
            $this->x($vec2->x),
            $this->y($vec2->y),
        );
    }
}
