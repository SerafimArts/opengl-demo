<?php

declare(strict_types=1);

namespace Bic\UI\Window\Handle;

use Bic\UI\Window\HandleInterface;

/**
 * X11 (XLib) window handle representation.
 *
 * @link https://www.x.org/releases/current/doc/libX11/libX11/libX11.html#Creating_Windows
 * @link https://www.x.org/releases/current/doc/libX11/libX11/libX11.html#Opening_the_Display
 */
final class XLibHandle implements HandleInterface
{
    /**
     * @param mixed $window (Window) X11 Window instance
     * @param mixed $display (Display) X11 Display instance
     */
    public function __construct(
        public readonly mixed $window,
        public readonly mixed $display,
    ) {
    }
}
