<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Serafim\Bic\NativeInterface;
use Serafim\SDL\RendererPtr;

/**
 * @method RendererPtr getPointer()
 */
interface RendererInterface extends NativeInterface
{
    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @return void
     */
    public function present(): void;
}
