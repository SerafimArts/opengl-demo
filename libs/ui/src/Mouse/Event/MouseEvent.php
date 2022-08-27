<?php

declare(strict_types=1);

namespace Bic\UI\Mouse\Event;

use Bic\UI\Event;

/**
 * @template TTargetWindow of WindowInterface
 *
 * @template-extends Event<TTargetWindow>
 */
abstract class MouseEvent extends Event
{
}
