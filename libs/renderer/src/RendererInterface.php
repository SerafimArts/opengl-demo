<?php

declare(strict_types=1);

namespace Bic\Renderer;

interface RendererInterface
{
    /**
     * @return void
     */
    public function clean(): void;

    /**
     * @return void
     */
    public function draw(): void;
}