<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Window;

use Serafim\Bic\NativeInterface;
use Serafim\SDL\WindowPtr;

/**
 * @method WindowPtr getPointer()
 */
interface WindowInterface extends NativeInterface
{

}
