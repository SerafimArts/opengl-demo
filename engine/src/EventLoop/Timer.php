<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\EventLoop;

/**
 * Class Timer
 */
class Timer
{
    /**
     * @var int
     */
    public const DEFAULT_UPDATE_RATE = 60;

    /**
     * @var float
     */
    public float $ops = 0;

    /**
     * @var float
     */
    private float $time;

    /**
     * @var float
     */
    private float $rate = 1;

    /**
     * Timer constructor.
     *
     * @param int $rate
     */
    public function __construct(int $rate = self::DEFAULT_UPDATE_RATE)
    {
        $this->rate($rate);
    }

    /**
     * @param float $now
     * @return void
     */
    public function touch(float $now = null): void
    {
        $this->time = $now ?? \microtime(true);
    }

    /**
     * @param int $rate
     * @return self
     */
    public function rate(int $rate): self
    {
        $this->rate = $rate === 0 ? 0 : 1 / $rate;

        return $this;
    }

    /**
     * @param float $now
     * @return float
     */
    public function delta(float $now): float
    {
        return $now - $this->time;
    }

    /**
     * @param float $now
     * @return float|null
     */
    public function next(float $now): ?float
    {
        $delta = $this->delta($now);

        if ($delta > 0) {
            $this->ops = 1 / $delta;
        }

        if ($delta > $this->rate) {
            $this->time = $now;

            return $delta;
        }

        return null;
    }
}
