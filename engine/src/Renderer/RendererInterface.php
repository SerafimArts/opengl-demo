<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Serafim\Bic\NativeInterface;
use SDL\RendererPtr;

/**
 * @method RendererPtr getPointer()
 */
interface RendererInterface extends NativeInterface
{
    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @return void
     */
    public function present(): void;
}
