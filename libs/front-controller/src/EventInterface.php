<?php

declare(strict_types=1);

namespace Bic\Controller;

/**
 * @template TTarget of object
 */
interface EventInterface
{
    /**
     * @return object
     */
    public function getTarget(): object;
}
