<?php

declare(strict_types=1);

namespace Bic\UI;

/**
 * @template TTarget of object
 *
 * @template-implements EventInterface<TTarget>
 */
abstract class Event implements EventInterface
{
    /**
     * @param TTarget $target
     */
    protected function __construct(
        public readonly object $target,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget(): object
    {
        return $this->target;
    }
}
