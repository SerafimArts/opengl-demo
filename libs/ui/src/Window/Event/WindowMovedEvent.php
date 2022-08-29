<?php

declare(strict_types=1);

namespace Bic\UI\Window\Event;

use Bic\UI\Window\WindowInterface;

/**
 * @package ui
 */
final class WindowMovedEvent extends WindowEvent
{
    /**
     * @param WindowInterface $window
     * @param int $x
     * @param int $y
     */
    public function __construct(
        WindowInterface $window,
        public readonly int $x,
        public readonly int $y,
    ) {
        parent::__construct($window);
    }
}
