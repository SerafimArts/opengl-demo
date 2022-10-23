<?php

declare(strict_types=1);

namespace Bic\Renderer;

interface RendererInterface
{
    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @return void
     */
    public function present(): void;
}