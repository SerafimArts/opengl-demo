<?php

declare(strict_types=1);

namespace Serafim\Bic;

use FFI\CData;
use FFI\CPtr;
use Serafim\SDL\SDL;
use Serafim\SDL\SDLNativeApiAutocomplete;

abstract class Native implements NativeInterface
{
    /**
     * @var SDL|SDLNativeApiAutocomplete
     */
    protected SDL $sdl;

    /**
     * @var CData|CPtr
     */
    public CData $ptr;

    public function __construct()
    {
        $this->sdl = SDL::getInstance();
    }

    /**
     * @return CData|CPtr
     */
    public function getPointer(): CData
    {
        return $this->ptr;
    }
}
