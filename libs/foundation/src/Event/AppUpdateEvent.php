<?php

declare(strict_types=1);

namespace Bic\Foundation\Event;

use Bic\Foundation\KernelInterface;

final class AppUpdateEvent extends AppEvent
{
    /**
     * @param KernelInterface $target
     * @param float $delta
     */
    public function __construct(
        KernelInterface $target,
        public float $delta = 0.0,
    ) {
        parent::__construct($target);
    }
}
