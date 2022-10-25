<?php

declare(strict_types=1);

namespace Bic\Renderer;

use FFI\CData;
use Serafim\SDL\SDL;

final class Renderer implements RendererInterface
{
    /**
     * @param object|SDL $ffi
     * @param CData $ptr
     */
    public function __construct(
        private readonly object $ffi,
        private readonly CData $ptr,
    ) {
    }

    public function clear(): void
    {
        $this->ffi->SDL_RenderClear($this->ptr);
    }

    public function present(): void
    {
        $this->ffi->SDL_RenderPresent($this->ptr);
    }
}
