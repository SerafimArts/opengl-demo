<?php

declare(strict_types=1);

namespace Bic\Foundation;

/**
 * @template TTarget of KernelInterface
 */
interface EventInterface
{
    /**
     * @return TTarget
     */
    public function getTarget(): KernelInterface;
}
