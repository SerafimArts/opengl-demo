<?php

declare(strict_types=1);

namespace Bic\UI\Window\Handle;

use Bic\UI\Window\HandleInterface;

/**
 * @link https://manpages.debian.org/experimental/libwayland-doc/wl_display.3.en.html
 */
class WaylandHandle implements HandleInterface
{
    /**
     * @param mixed $display (wl_display) Represents a connection to the
     *        compositor and acts as a proxy to the wl_display singleton object.
     * @param mixed $surface (wl_surface) A surface is a rectangular area that
     *        may be displayed on zero or more outputs, and shown any number of
     *       times at the compositor's discretion. They can present wl_buffers,
     *       receive user input, and define a local coordinate system.
     */
    public function __construct(
        public readonly mixed $display,
        public readonly mixed $surface,
    ) {
    }
}
