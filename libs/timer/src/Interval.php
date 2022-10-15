<?php

declare(strict_types=1);

namespace Bic\Timer;

class Interval extends Timer
{
    /**
     * {@inheritDoc}
     */
    final public function start(): void
    {
        if ($this->running === false) {
            $this->running = true;

            if (\Fiber::getCurrent()) {
                while ($this->running) {
                    $this->tick();
                    \Fiber::suspend();
                }
            } else {
                while (true) {
                    $this->tick();
                    \usleep(1);
                }
            }
        }
    }
}
