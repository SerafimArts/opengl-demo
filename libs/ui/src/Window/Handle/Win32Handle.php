<?php

declare(strict_types=1);

namespace Bic\UI\Window\Handle;

use Bic\UI\Window\HandleInterface;
use FFI\CData;

/**
 * Win32 window handle representation.
 *
 * @link https://learn.microsoft.com/en-us/windows/win32/gdi/device-context-types
 * @link https://learn.microsoft.com/en-us/windows/win32/winmsg/windows
 */
final class Win32Handle implements HandleInterface
{
    /**
     * @param mixed $window (HWND) A handle to a window.
     * @param mixed $dc (HDC) A handle to a device context (DC).
     * @param mixed $instance (HINSTANCE) A handle to an instance. This is the
     *        base address of the module in memory. HMODULE and HINSTANCE are
     *        the same today, but represented different things in 16-bit Windows.
     */
    public function __construct(
        public readonly mixed $window,
        public readonly mixed $dc,
        public readonly mixed $instance,
    ) {
    }
}
