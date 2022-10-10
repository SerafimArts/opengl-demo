<?php

declare(strict_types=1);

namespace Bic\UI\Mouse\Event;

use Bic\UI\Mouse\ButtonInterface;
use Bic\UI\Mouse\Event;
use Bic\UI\Window\WindowInterface;

abstract class MouseButtonEvent extends Event
{
    public function __construct(
        WindowInterface $target,
        public readonly int $x,
        public readonly int $y,
        public readonly ButtonInterface $button,
    ) {
        parent::__construct($target);
    }
}
