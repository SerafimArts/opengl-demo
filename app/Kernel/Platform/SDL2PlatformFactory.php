<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Kernel\Platform;

use Bic\Lib\SDL2\Platform;

/**
 * @template-implements PlatformFactoryInterface<Platform>
 */
final class SDL2PlatformFactory implements PlatformFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getPlatform(string $family = \PHP_OS_FAMILY): Platform
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => Platform::WINDOWS,
            'Solaris', 'Linux' => Platform::LINUX,
            'BSD' => Platform::FREEBSD,
            'Darwin' => Platform::DARWIN,
        };
    }
}
