<?php

declare(strict_types=1);

namespace Serafim\Bic\Map;

use FFI\CData;
use Serafim\SDL\Kernel\Video\BlendMode;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;
use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Texture;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;
use Serafim\Bic\Util;

class Layer extends View
{
    /**
     * @var int
     */
    public int $width;

    /**
     * @var int
     */
    public int $height;

    /**
     * @var float
     */
    public float $opacity = 255;

    /**
     * @var bool
     */
    public bool $visible = true;

    /**
     * @var CData|RectPtr
     */
    public CData $dest;

    /**
     * @var array|Tile[]
     */
    private array $tiles = [];

    /**
     * @var Texture
     */
    private ?Texture $layer = null;

    /**
     * @var CData|RectPtr
     */
    private CData $clip;

    /**
     * @param Lifecycle $game
     * @param int $width
     * @param int $height
     */
    public function __construct(Lifecycle $game, int $width, int $height)
    {
        parent::__construct($game);

        $this->width = $width;
        $this->height = $height;

        $this->clip = Util::createRect();
        $this->dest = Util::createRect();
    }

    /**
     * @param Tile $tile
     * @return void
     */
    public function addTile(Tile $tile): void
    {
        $this->dest->w = $this->clip->w = \max($this->clip->w, $tile->dest->x + $tile->dest->w);
        $this->dest->h = $this->clip->h = \max($this->clip->h, $tile->dest->y + $tile->dest->h);

        $this->tiles[] = $tile;
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        if ($this->layer === null) {
            $this->prerender($renderer);
        }

        $this->layer->destination->x = (int)$transform->x($this->dest->x);
        $this->layer->destination->y = (int)$transform->y($this->dest->y);
        $this->layer->destination->w = (int)$transform->w($this->dest->w);
        $this->layer->destination->h = (int)$transform->h($this->dest->h);

        $this->sdl->SDL_RenderCopy(
            $renderer->getPointer(),
            $this->layer->ptr,
            $this->layer->source,
            $this->layer->destination
        );
    }

    /**
     * @param RendererInterface $renderer
     * @return void
     */
    private function prerender(RendererInterface $renderer): void
    {
        $format = $this->sdl->SDL_GetWindowPixelFormat($this->game->window->getPointer());

        $texture = $this->sdl->SDL_CreateTexture(
            $renderer->getPointer(),
            $format,
            SDL::SDL_TEXTUREACCESS_TARGET,
            $this->clip->w,
            $this->clip->h
        );

        $this->layer = new Texture($texture, $this->clip);
        $this->layer->blending(BlendMode::SDL_BLENDMODE_BLEND);

        $this->layer->openTarget($renderer);

        foreach ($this->tiles as $tile) {
            $this->sdl->SDL_RenderCopy(
                $renderer->getPointer(),
                $tile->texture->ptr,
                $tile->clip,
                $tile->dest
            );
        }

        $this->layer->closeTarget($renderer);
    }
}
