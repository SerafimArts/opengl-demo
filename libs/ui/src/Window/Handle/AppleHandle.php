<?php

declare(strict_types=1);

namespace Bic\UI\Window\Handle;

use Bic\UI\Window\HandleInterface;

/**
 * @link https://developer.apple.com/documentation/appkit/nswindow
 */
class AppleHandle implements HandleInterface
{
    /**
     * @param mixed $window (NSWindow) An apple (cocoa) window reference.
     */
    public function __construct(
        public readonly mixed $window,
    ) {
    }
}
