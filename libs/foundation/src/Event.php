<?php

declare(strict_types=1);

namespace Bic\Foundation;

/**
 * @template TTarget of KernelInterface
 *
 * @template-implements EventInterface<TTarget>
 */
abstract class Event implements EventInterface
{
    /**
     * @param TTarget $target
     */
    public function __construct(
        public readonly KernelInterface $target,
    ) {
    }

    /**
     * @return TTarget
     */
    public function getTarget(): KernelInterface
    {
        return $this->target;
    }
}
