<?php

declare(strict_types=1);

namespace Bic\Controller;

/**
 * @template TTarget of object
 * @template-implements EventInterface<TTarget>
 */
abstract class Event implements EventInterface
{
    /**
     * @param TTarget $controller
     */
    public function __construct(
        public readonly object $controller,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget(): object
    {
        return $this->controller;
    }
}
