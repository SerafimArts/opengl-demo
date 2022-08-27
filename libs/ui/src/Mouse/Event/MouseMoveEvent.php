<?php

declare(strict_types=1);

namespace Bic\UI\Mouse\Event;

use Bic\UI\Window\WindowInterface;

final class MouseMoveEvent extends MouseEvent
{
    public function __construct(
        WindowInterface $target,
        public readonly int $x,
        public readonly int $y,
    ) {
        parent::__construct($target);
    }
}
