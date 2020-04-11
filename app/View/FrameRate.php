<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\View;

use FFI\CData;
use Serafim\Bic\EventLoop\OrderedEventLoop;
use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Surface;
use Serafim\Bic\Renderer\Texture;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;
use Serafim\SDL\Color;
use Serafim\SDL\Rect;
use Serafim\SDL\RectPtr;
use Serafim\SDL\SDL;
use Serafim\SDL\SurfacePtr;
use Serafim\SDL\TTF\FontPtr;
use Serafim\SDL\TTF\Hinting;
use Serafim\SDL\TTF\TTF;

/**
 * Class FrameRate
 */
class FrameRate extends View
{
    /**
     * @var TTF
     */
    private TTF $ttf;

    /**
     * @var CData|FontPtr
     */
    private CData $font;

    /**
     * @var CData|Color
     */
    public CData $color;

    /**
     * @var CData|SurfacePtr
     */
    private CData $surface;

    /**
     * @var CData|Rect
     */
    public CData $position;

    /**
     * @var CData|RectPtr
     */
    private CData $dest;

    /**
     * FrameRate constructor.
     *
     * @param Lifecycle $game
     */
    public function __construct(Lifecycle $game)
    {
        parent::__construct($game);

        $this->ttf = TTF::getInstance();
        $this->font = $this->ttf->open(
            $game->app->resourcesPath('bender.ttf'),
            (int)$game->viewport->y(12)
        );

        $this->ttf->setHinting($this->font, Hinting::TTF_HINTING_MONO);
        $this->position = $this->sdl->new(Rect::class);
        $this->position->x = $this->position->y = 10;

        $this->dest = SDL::addr($this->position);
        $this->color = $this->ttf->new(Color::class, false);
        $this->color->r = $this->color->g = $this->color->b = 255;
        $this->color->a = 200;

        $this->updateFrameRate(10);
    }

    /**
     * @param float $rate
     * @return void
     */
    private function updateFrameRate(float $rate = .0): void
    {
        $text = \number_format($rate, 1) . ' FPS';

        $this->surface = $this->ttf->renderBlended($this->font, $text, $this->color);

        [$width, $height] = $this->ttf->getSize($this->font, $text);

        $this->position->w = (int)$this->game->viewport->w($width);
        $this->position->h = (int)$this->game->viewport->h($height);
    }

    /**
     * @param float $delta
     * @return void
     */
    public function update(float $delta): void
    {
        $loop = $this->game->loop;

        if ($loop instanceof OrderedEventLoop) {
            $this->updateFrameRate($loop->render->ops);
        }
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        $texture = $this->sdl->SDL_CreateTextureFromSurface($renderer->getPointer(), $this->surface);

        $this->sdl->SDL_RenderCopy($renderer->getPointer(), $texture, null, $this->dest);
    }
}
