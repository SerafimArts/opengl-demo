<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use FFI\CData;
use Serafim\SDL\SDLNativeApiAutocomplete;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Native;
use Serafim\Bic\Util;
use Serafim\SDL\Kernel\Video\BlendMode;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;
use Serafim\SDL\TexturePtr;

/**
 * @method TexturePtr getPointer()
 * @property-read TexturePtr $ptr
 */
class Texture extends Native
{
    /**
     * @var CData|RectPtr
     */
    public CData $source;

    /**
     * @var CData|RectPtr
     */
    public CData $destination;

    /**
     * Texture constructor.
     *
     * @param CData $texture
     * @param CData|RectPtr $clip
     */
    public function __construct(CData $texture, CData $clip)
    {
        parent::__construct();

        $this->ptr = $texture;
        $this->source = SDL::addr(Util::copyRect($clip));
        $this->destination = Util::createRect($clip->w, $clip->h);
    }

    /**
     * @param RendererInterface $renderer
     * @return void
     */
    public function openTarget(RendererInterface $renderer): void
    {
        $this->sdl->SDL_SetRenderTarget($renderer->getPointer(), $this->ptr);
    }

    /**
     * @param RendererInterface $renderer
     * @return void
     */
    public function closeTarget(RendererInterface $renderer): void
    {
        $this->sdl->SDL_SetRenderTarget($renderer->getPointer(), null);
    }

    /**
     * @param TransformationInterface $transform
     * @param float $w
     * @param float $h
     * @param float $x
     * @param float $y
     * @return void
     */
    public function dest(TransformationInterface $transform, float $w, float $h, float $x = 0, float $y = 0): void
    {
        $this->destination[0]->x = (int)$transform->x($x);
        $this->destination[0]->y = (int)$transform->y($y);
        $this->destination[0]->w = (int)$transform->w($w);
        $this->destination[0]->h = (int)$transform->h($h);
    }

    /**
     * @param TransformationInterface $transform
     * @param Vector2 $size
     * @param Vector2|null $pos
     * @return void
     */
    public function destFromVec2(TransformationInterface $transform, Vector2 $size, Vector2 $pos = null): void
    {
        [$x, $y] = $pos ? [$pos->x, $pos->y] : [0, 0];

        $this->dest($transform, $size->x, $size->y, $x, $y);
    }

    /**
     * @param RendererInterface $renderer
     * @param string $pathname
     * @return $this
     */
    public static function fromPathname(RendererInterface $renderer, string $pathname): self
    {
        /** @var SDLNativeApiAutocomplete $sdl */
        $sdl = SDL::getInstance();

        $surface = Surface::fromPathname($pathname);

        $texture = $sdl->SDL_CreateTextureFromSurface($renderer->getPointer(), $surface->getPointer());

        return new static($texture, $surface->getClipRect());
    }

    /**
     * @param int $mode
     * @return void
     */
    public function blending(int $mode = BlendMode::SDL_BLENDMODE_NONE): void
    {
        $this->sdl->SDL_SetTextureBlendMode($this->ptr, $mode);
    }

    /**
     * @param float $alpha
     * @return void
     */
    public function alpha(float $alpha): void
    {
        $this->sdl->SDL_SetTextureAlphaMod($this->ptr, $alpha);
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return void
     */
    public function color(int $red = 0, int $green = 0, int $blue = 0): void
    {
        $this->sdl->SDL_SetTextureColorMod($this->ptr, $red, $green, $blue);
    }
}
