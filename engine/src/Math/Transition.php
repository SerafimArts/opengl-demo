<?php

declare(strict_types=1);

namespace Serafim\Bic\Math;

class Transition
{
    /**
     * @var float
     */
    public float $value;

    /**
     * @var float
     */
    private float $source;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->source = $this->value = $value;
    }

    /**
     * @param float $value
     * @return void
     */
    public function transitionTo(float $value): void
    {
        $this->source = $value;
    }

    /**
     * @param float $delta
     * @return void
     */
    public function update(float $delta): void
    {
        if ($this->value < $this->source) {
            $this->value += $delta;
        }
    }
}
