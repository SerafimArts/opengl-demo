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
 * Class Opacity
 */
class Opacity
{
    /**
     * @var float
     */
    public float $value;

    /**
     * Opacity constructor.
     *
     * @param float $value
     */
    public function __construct(float $value = 255.0)
    {
        $this->value = $value;
    }
}
