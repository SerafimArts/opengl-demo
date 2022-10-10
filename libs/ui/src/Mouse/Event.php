<?php

declare(strict_types=1);

namespace Bic\UI\Mouse;

use Bic\UI\Event as UIEvent;
use Bic\UI\Window\WindowInterface;

/**
 * @template TTargetWindow of WindowInterface
 *
 * @template-extends UIEvent<TTargetWindow>
 */
abstract class Event extends UIEvent
{
}
