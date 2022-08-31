<?php

declare(strict_types=1);

namespace Bic\Renderer;

/**
 * @template TTarget of RendererInterface
 * @package renderer
 */
interface EventInterface
{
    /**
     * @return TTarget
     */
    public function getTarget(): RendererInterface;
}