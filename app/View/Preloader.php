<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\View;

use App\Game;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Texture;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;
use Serafim\Bic\Renderer\Viewport\ViewportInterface;
use Serafim\Bic\View\LoadedTexture;

/**
 * Class Preloader
 */
class Preloader extends View
{
    /**
     * @var float
     */
    public float $rotation = 0;

    /**
     * @var Vector2
     */
    public Vector2 $size;

    /**
     * @var Texture
     */
    private Texture $icon;

    /**
     * @var Texture
     */
    private Texture $loading;

    /**
     * Preloader constructor.
     *
     * @param Game $game
     * @param Vector2|null $position
     */
    public function __construct(Game $game, Vector2 $position = null)
    {
        parent::__construct($game);

        $position = $position ?? new Vector2();

        $this->icon = $this->texture('img/preloader/icon.png');
        $this->loading = $this->texture('img/preloader/loading.png');

        $this->icon->destination->x = (int)$position->x;
        $this->icon->destination->y = (int)$position->y;
        $this->loading->destination->x = (int)$position->x;
        $this->loading->destination->y = (int)$position->y;
    }

    /**
     * @param float $delta
     * @return void
     */
    public function update(float $delta): void
    {
        $this->rotation += $delta * 600;

        if ($this->rotation >= 360) {
            $this->rotation -= 360;
        }
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        $destination = $transform->transform($this->icon->destination);
        $this->sdl->SDL_RenderCopy($renderer->getPointer(), $this->icon->getPointer(), null, $destination);

        $destination = $transform->transform($this->loading->destination);
        $this->sdl->SDL_RenderCopyEx(
            $renderer->getPointer(),
            $this->loading->getPointer(),
            null,
            $destination,
            $this->rotation,
            null,
            0
        );
    }
}
