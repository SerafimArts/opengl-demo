<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic;

use FFI\CData;
use FFI\CPtr;
use Serafim\SDL\SDL;
use Serafim\SDL\SDLNativeApiAutocomplete;

/**
 * Class Native
 */
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

    /**
     * Native constructor.
     */
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
