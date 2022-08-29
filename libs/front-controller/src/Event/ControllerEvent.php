<?php

declare(strict_types=1);

namespace Bic\Controller\Event;

use Bic\Controller\Event;

/**
 * @template TTarget of object
 * @template-implements EventInterface<TTarget>
 */
abstract class ControllerEvent extends Event
{
}
