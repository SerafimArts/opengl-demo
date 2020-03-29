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
 * Class Vector2
 */
class Vector2 extends Vector
{
    /**
     * @var float
     */
    public float $y = 0;

    /**
     * Vector2 constructor.
     *
     * @param float $x
     * @param float $y
     */
    public function __construct(float $x = 0, float $y = 0)
    {
        parent::__construct($x);

        $this->y = $y;
    }
}
