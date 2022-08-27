<?php

declare(strict_types=1);

namespace Bic\UI\Window\Event;

use Bic\UI\Event;

/**
 * @template TTargetWindow of WindowInterface
 *
 * @template-extends Event<TTargetWindow>
 */
abstract class WindowEvent extends Event
{
}
