<?php

declare(strict_types=1);

namespace Bic\Timer;

/**
 * @psalm-consistent-constructor
 */
class Timer implements TimerInterface
{
    /**
     * @var float
     */
    private float $updatedAt = 0.0;

    /**
     * @var bool
     */
    protected bool $running = false;

    public function __construct(
        private readonly \Closure $handler,
        private readonly int|float $interval = 1.0,
    ) {
    }

    /**
     * @param \Closure $handler
     * @param int|float $interval
     *
     * @return static
     */
    public static function create(
        \Closure $handler,
        int|float $interval = 1.0,
    ): static {
        $instance = new static($handler, $interval);
        $instance->start();

        return $instance;
    }

    public function cancel(): void
    {
        $this->running = false;
    }

    /**
     * @return bool
     */
    protected function tick(): bool
    {
        /** @var float $now */
        $now = \microtime(true);

        // Timer initialization
        if ($this->updatedAt === 0.0) {
            $this->updatedAt = $now;

            return false;
        }

        if ($this->updatedAt + $this->interval <= $now) {
            $this->updatedAt += $this->interval;

            ($this->handler)();

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function start(): void
    {
        if ($this->running === false) {
            $this->running = true;

            if (\Fiber::getCurrent()) {
                while ($this->running) {
                    $this->running = !$this->tick();
                    \Fiber::suspend();
                }
            } else {
                while (!$this->tick()) {
                    \usleep(1);
                }
            }
        }
    }
}
