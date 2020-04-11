<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use FFI\CData;
use Serafim\SDL\RectPtr;

/**
 * Interface TransformationInterface
 */
interface TransformationInterface
{
    /**
     * @param float $x
     * @return float
     */
    public function x(float $x): float;

    /**
     * @param float $y
     * @return float
     */
    public function y(float $y): float;

    /**
     * @param float $w
     * @return float
     */
    public function w(float $w): float;

    /**
     * @param float $h
     * @return float
     */
    public function h(float $h): float;

    /**
     * @param CData|RectPtr $rect
     * @return CData|RectPtr
     */
    public function transform(CData $rect): CData;
}
