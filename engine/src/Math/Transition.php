<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Math;

/**
 * Class Transition
 */
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
     * Transition constructor.
     *
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
