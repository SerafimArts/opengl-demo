<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic;

use FFI\CData;
use FFI\CPtr;

/**
 * Interface NativeInterface
 */
interface NativeInterface
{
    /**
     * @return CData|CPtr
     */
    public function getPointer(): CData;
}
