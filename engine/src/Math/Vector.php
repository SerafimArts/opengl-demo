<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Math;

/**
 * Class Vector
 */
class Vector
{
    /**
     * @var float
     */
    public float $x = 0;

    /**
     * Vector constructor.
     *
     * @param float $x
     */
    public function __construct(float $x = 0)
    {
        $this->x = $x;
    }
}
