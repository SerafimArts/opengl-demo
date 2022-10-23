<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Internal;

use Bic\UI\SDL\Kernel\SysWMType;
use Bic\UI\Window\Handle\AppleHandle;
use Bic\UI\Window\Handle\WaylandHandle;
use Bic\UI\Window\Handle\Win32Handle;
use Bic\UI\Window\Handle\XLibHandle;
use Bic\UI\Window\HandleInterface;
use FFI\CData;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
final class HandlerFactory
{
    /**
     * @var CData
     */
    private readonly CData $info;

    /**
     * @param object $ffi
     */
    public function __construct(
        private readonly object $ffi,
    ) {
        $this->info = $this->ffi->new('SDL_SysWMinfo');
        $this->ffi->SDL_GetVersion(\FFI::addr($this->info->version));
    }

    /**
     * @param CData $window
     * @return HandleInterface
     */
    public function get(CData $window): HandleInterface
    {
        $this->ffi->SDL_GetWindowWMInfo($window, \FFI::addr($this->info));

        return match ($this->info->subsystem) {
            SysWMType::SDL_SYSWM_WINDOWS => new Win32Handle(
                window: $this->info->info->win->window,
                dc: $this->info->info->win->hdc,
                instance: $this->info->info->win->hinstance,
            ),
            SysWMType::SDL_SYSWM_X11 => new XLibHandle(
                window: $this->info->info->x11->window,
                display: $this->info->info->x11->display,
            ),
            SysWMType::SDL_SYSWM_COCOA => new AppleHandle(
                window: $this->info->info->cocoa->window,
            ),
            SysWMType::SDL_SYSWM_WAYLAND => new WaylandHandle(
                display: $this->info->info->wl->display,
                surface: $this->info->info->wl->surface,
            ),
            default => throw new \LogicException(
                'Could not resolve window handler'
            ),
        };
    }
}