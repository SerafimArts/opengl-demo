<?php

declare(strict_types=1);

namespace Bic\UI\Mouse\Event;

use Bic\UI\Mouse\Event;
use Bic\UI\Mouse\Wheel;
use Bic\UI\Window\WindowInterface;

class MouseWheelEvent extends Event
{
    public function __construct(
        WindowInterface $target,
        public readonly Wheel $wheel,
    ) {
        parent::__construct($target);
    }
}
