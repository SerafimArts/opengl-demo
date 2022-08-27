<?php

declare(strict_types=1);

namespace Bic\UI;

use Bic\UI\Window\WindowInterface;

/**
 * @template TTarget of WindowInterface
 */
interface EventInterface
{
    /**
     * @return TTarget
     */
    public function getTarget(): WindowInterface;
}
