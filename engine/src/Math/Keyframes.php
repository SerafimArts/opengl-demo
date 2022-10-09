<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Keyframes
{
    /**
     * @var int
     */
    public const KEY_FROM = 0;

    /**
     * @var int
     */
    public const KEY_TO = 1;

    /**
     * @var int
     */
    public const KEY_CALLBACK = 2;

    /**
     * @var float
     */
    private float $timer = 0;

    /**
     * @var array
     */
    private array $keyframes = [];

    /**
     * @param float $from
     * @param float $to
     * @param \Closure $callback
     * @return void
     */
    public function add(float $from, float $to, \Closure $callback): void
    {
        $this->keyframes[] = [
            self::KEY_FROM => $from,
            self::KEY_TO => $to,
            self::KEY_CALLBACK => $callback
        ];
    }

    /**
     * @param float $from
     * @param \Closure $callback
     * @return void
     */
    public function from(float $from, \Closure $callback): void
    {
        $this->add($from, \INF, $callback);
    }

    /**
     * @param float $to
     * @param \Closure $callback
     * @return void
     */
    public function to(float $to, \Closure $callback): void
    {
        $this->add(0, $to, $callback);
    }

    /**
     * @param float $delta
     * @param array $args
     * @return void
     */
    public function update(float $delta, ...$args): void
    {
        $this->timer += $delta;

        foreach ($this->keyframes as [$from, $to, $callback]) {
            if ($this->timer > $from && $this->timer < $to) {
                $callback($this->timer - $from, ...$args);
            }
        }
    }
}
