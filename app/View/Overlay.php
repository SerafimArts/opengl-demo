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

/**
 * Class Overlay
 */
class Overlay extends View
{
    /**
     * @var Texture
     */
    private Texture $overlay;

    /**
     * @return void
     */
    protected function load(): void
    {
        $this->overlay = $this->texture('img/loading/overlay.png');
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        $this->sdl->SDL_RenderCopy($renderer->getPointer(), $this->overlay->getPointer(), null, null);
    }
}
