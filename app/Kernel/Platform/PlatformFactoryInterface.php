<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Platform;

/**
 * @template T of object
 */
interface PlatformFactoryInterface
{
    /**
     * @psalm-suppress InvalidParamDefault
     * @param non-empty-string $family
     * @return T
     */
    public function getPlatform(string $family = \PHP_OS_FAMILY): object;
}
