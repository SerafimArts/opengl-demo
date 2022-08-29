<?php

declare(strict_types=1);

namespace Bic\UI;

use Bic\UI\Window\WindowInterface;

/**
 * @template TTarget of WindowInterface
 *
 * @template-implements EventInterface<TTarget>
 * @package ui
 */
abstract class Event implements EventInterface
{
    /**
     * @param TTarget $target
     */
    public function __construct(
        public readonly WindowInterface $target,
    ) {
    }

    /**
     * @return TTarget
     */
    public function getTarget(): WindowInterface
    {
        return $this->target;
    }
}
