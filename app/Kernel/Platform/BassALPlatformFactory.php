<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Platform;

use Bic\Lib\BassAL\Platform;

/**
 * @template-implements PlatformFactoryInterface<Platform>
 */
final class BassALPlatformFactory implements PlatformFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getPlatform(string $family = \PHP_OS_FAMILY): Platform
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Platform::WINDOWS,
            'BSD', 'Solaris', 'Linux' => Platform::LINUX,
            'Darwin' => Platform::DARWIN,
        };
    }
}
