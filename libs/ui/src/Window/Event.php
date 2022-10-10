<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Bic\UI\Event as UIEvent;

/**
 * @template TTarget of WindowInterface
 *
 * @template-extends UIEvent<TTarget>
 */
abstract class Event extends UIEvent
{
    /**
     * @param TTarget $target
     */
    public function __construct(WindowInterface $target)
    {
        parent::__construct($target);
    }
}
