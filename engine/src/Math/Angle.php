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
 * Class Angle
 */
class Angle
{
    /**
     * @var float
     */
    public float $value;

    /**
     * Angle constructor.
     *
     * @param float $angle
     */
    public function __construct(float $angle = 0.0)
    {
        $this->value = $angle;
    }
}
