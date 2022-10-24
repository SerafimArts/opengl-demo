<?php

declare(strict_types=1);

namespace Bic\Renderer;

use Bic\UI\Window\WindowInterface;

interface FactoryInterface
{
    /**
     * @param WindowInterface $window
     * @return RendererInterface
     */
    public function create(WindowInterface $window): RendererInterface;
}
