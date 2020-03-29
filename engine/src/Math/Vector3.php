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
 * Class Vector3
 */
class Vector3 extends Vector2
{
    /**
     * @var float
     */
    public float $z = 0;

    /**
     * Vector3 constructor.
     *
     * @param float $x
     * @param float $y
     * @param float $z
     */
    public function __construct(float $x = 0, float $y = 0, float $z = 0)
    {
        parent::__construct($x, $y);

        $this->z = $z;
    }
}
