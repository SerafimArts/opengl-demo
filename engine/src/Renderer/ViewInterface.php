<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;
"*"
interface ViewInterface
{
    /**
     * @param RendererInterface $renderer
     * @param TransformationInterface $transform
     * @return void
     */
    public function render(RendererInterface $renderer, TransformationInterface $transform): void;
}
