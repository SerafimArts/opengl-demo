<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\View;

use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Texture;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;
use Serafim\SDL\Kernel\Video\BlendMode;

/**
 * Class Noise
 */
class Noise extends View
{
    /**
     * @var float
     */
    public float $opacity = 100;

    /**
     * @var array
     */
    private array $textures = [];

    /**
     * @var int
     */
    private int $index = 0;

    /**
     * @return void
     */
    public function load(): void
    {
        foreach (\glob($this->resources('img/noise/*.png')) as $texture) {
            $this->textures[] = $this->texture($texture);
        }
    }

    /**
     * @param float $delta
     * @return void
     */
    public function update(float $delta): void
    {
        $this->index++;

        if ($this->index >= \count($this->textures)) {
            $this->index = 0;
        }
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        /** @var Texture $current */
        $current = $this->textures[$this->index];

        $current->blending(BlendMode::SDL_BLENDMODE_BLEND);
        $current->alpha($this->opacity);

        $source = $transform->getSource();


        $current->destination->x = 0;
        do {
            $current->destination->y = 0;
            do {
                $this->sdl->renderCopy(
                    $renderer->getPointer(),
                    $current->getPointer(),
                    null,
                    $current->destination
                );
                $current->destination->y += $current->destination->h;
            } while ($current->destination->y < $source->y);

            $current->destination->x += $current->destination->w;
        } while ($current->destination->x < $source->x);
    }
}
