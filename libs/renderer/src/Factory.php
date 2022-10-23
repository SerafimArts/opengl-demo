<?php

declare(strict_types=1);

namespace Bic\Renderer;

use Bic\UI\SDL\Window;
use Bic\UI\Window\Handle\AppleHandle;
use Bic\UI\Window\Handle\WaylandHandle;
use Bic\UI\Window\Handle\Win32Handle;
use Bic\UI\Window\Handle\XLibHandle;
use Bic\UI\Window\WindowInterface;
use FFI\CData;
use Serafim\SDL\Kernel\Video\RendererFlags;
use Serafim\SDL\SDL;

final class Factory implements FactoryInterface
{
    /**
     * @var \WeakMap<WindowInterface, RendererInterface>
     */
    private readonly \WeakMap $references;

    /**
     * @param object|SDL $ffi
     */
    public function __construct(
        private readonly object $ffi,
    ) {
        $this->references = new \WeakMap();
    }

    /**
     * {@inheritDoc}
     */
    public function create(WindowInterface $window): RendererInterface
    {
        if (isset($this->references[$window])) {
            return $this->references[$window];
        }

        $pointer = $this->ffi->SDL_CreateRenderer(
            $this->getWindow($window),
            0,
            RendererFlags::SDL_RENDERER_ACCELERATED
        );

        return $this->references[$window] = new Renderer($this->ffi, $pointer);
    }

    /**
     * @param WindowInterface $window
     * @return CData
     */
    private function getWindow(WindowInterface $window): CData
    {
        if ($window instanceof Window) {
            return $this->ffi->cast('SDL_Window*', $window->getCData());
        }

        $handle = $window->getHandle();

        // TODO Can throw exceptions (like 0xC000041D)
        return match(true) {
            $handle instanceof Win32Handle => $this->ffi->SDL_CreateWindowFrom($handle->window),
            $handle instanceof XLibHandle => $this->ffi->SDL_CreateWindowFrom($handle->window),
            $handle instanceof WaylandHandle => $this->ffi->SDL_CreateWindowFrom($handle->surface),
            $handle instanceof AppleHandle => $this->ffi->SDL_CreateWindowFrom($handle->window),
        };
    }
}