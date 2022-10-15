<?php

declare(strict_types=1);

namespace Bic\Timer;

interface TimerInterface
{
    /**
     * @return void
     */
    public function cancel(): void;

    /**
     * @return void
     */
    public function start(): void;
}
