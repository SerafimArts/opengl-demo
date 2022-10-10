<?php

declare(strict_types=1);

namespace Bic\UI\SDL;

use Bic\UI\Factory as BaseFactory;
use Bic\UI\SDL\Kernel\SysWMType;
use Bic\UI\SDL\Kernel\WindowFlags;
use Bic\UI\SDL\Kernel\WindowPosition;
use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\Handle\AppleHandle;
use Bic\UI\Window\Handle\WaylandHandle;
use Bic\UI\Window\Handle\Win32Handle;
use Bic\UI\Window\Handle\XLibHandle;
use Bic\UI\Window\HandleInterface;
use Bic\UI\Window\Mode;
use Bic\UI\Window\WindowInterface;
use FFI\CData;

/**
 * @template-extends BaseFactory<Window, CreateInfo>
 */
final class Factory extends BaseFactory
{
    /**
     * @var int
     */
    private const DEFAULT_FLAGS = WindowFlags::SDL_WINDOW_OPENGL
        | WindowFlags::SDL_WINDOW_MOUSE_CAPTURE
        | WindowFlags::SDL_WINDOW_INPUT_FOCUS
        // | WindowFlags::SDL_WINDOW_INPUT_GRABBED
    ;

    /**
     * @param object|\FFI $sdl
     */
    public function __construct(
        private readonly object $sdl,
    ) {
        parent::__construct();
    }

    /**
     * @psalm-taint-sink file $library
     *
     * @param non-empty-string|null $library
     *
     * @return static
     */
    public static function fromLibrary(string $library = null): self
    {
        return new self(new Library($library));
    }

    /**
     * @param CreateInfo $info
     *
     * @return WindowInterface
     */
    protected function instance(CreateInfo $info): WindowInterface
    {
        $x = $info->position?->x ?? WindowPosition::SDL_WINDOWPOS_CENTERED;
        $y = $info->position?->y ?? WindowPosition::SDL_WINDOWPOS_CENTERED;

        $flags = $this->getFlags($info);

        $ptr = $this->sdl->SDL_CreateWindow($info->title, $x, $y, $info->size->width, $info->size->height, $flags);

        return new Window($this->sdl, $ptr, $this->getHandle($ptr), $info, $this->detach(...));
    }

    /**
     * @param CData $ptr
     *
     * @return HandleInterface
     */
    private function getHandle(CData $ptr): HandleInterface
    {
        $info = $this->sdl->new('SDL_SysWMinfo');
        $this->sdl->SDL_GetVersion(\FFI::addr($info->version));
        $this->sdl->SDL_GetWindowWMInfo($ptr, \FFI::addr($info));

        return match ($info->subsystem) {
            SysWMType::SDL_SYSWM_WINDOWS => new Win32Handle(
                window: $info->info->win->window,
                dc: $info->info->win->hdc,
                instance: $info->info->win->hinstance,
            ),
            SysWMType::SDL_SYSWM_X11 => new XLibHandle(
                window: $info->info->x11->window,
                display: $info->info->x11->display,
            ),
            SysWMType::SDL_SYSWM_COCOA => new AppleHandle(
                window: $info->info->cocoa->window,
            ),
            SysWMType::SDL_SYSWM_WAYLAND => new WaylandHandle(
                display: $info->info->wl->display,
                surface: $info->info->wl->surface,
            ),
            default => null,
        };
    }

    /**
     * @param CreateInfo $info
     *
     * @return int
     */
    private function getFlags(CreateInfo $info): int
    {
        return self::DEFAULT_FLAGS | match ($info->mode) {
            Mode::DESKTOP_FULLSCREEN => WindowFlags::SDL_WINDOW_FULLSCREEN_DESKTOP,
            Mode::FULLSCREEN => WindowFlags::SDL_WINDOW_FULLSCREEN,
            Mode::HIDDEN => WindowFlags::SDL_WINDOW_HIDDEN,
            default => 0,
        };
    }
}
