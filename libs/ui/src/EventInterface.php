<?php

declare(strict_types=1);

namespace Bic\UI;

/**
 * @template TTarget of object
 */
interface EventInterface
{
    /**
     * @return TTarget
     */
    public function getTarget(): object;
}
