<?php

declare(strict_types=1);

namespace Bic\Renderer;

/**
 * @template TTarget of RendererInterface
 *
 * @template-implements EventInterface<TTarget>
 * @package renderer
 */
abstract class Event implements EventInterface
{
    /**
     * @param RendererInterface $renderer
     * @param float $delta
     */
    public function __construct(
        public readonly RendererInterface $renderer,
        public readonly float $delta = 0.0,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * @return float
     */
    public function getDelta(): float
    {
        return $this->delta;
    }
}