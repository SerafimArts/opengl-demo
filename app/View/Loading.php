<?php

declare(strict_types=1);

namespace App\View;

use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Renderer\Texture;
use Serafim\Bic\Renderer\TransformationInterface;
use Serafim\Bic\Renderer\View;

class Loading extends View
{
    /**
     * @var Texture
     */
    private Texture $splash;

    /**
     * @return void
     */
    public function load(): void
    {
        $this->splash = $this->texture('img/loading/bg.png');
    }

    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void
    {
        $this->sdl->SDL_RenderCopy($renderer->getPointer(), $this->splash->getPointer(), null, null);
    }
}
