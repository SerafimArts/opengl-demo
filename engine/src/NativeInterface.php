<?php

declare(strict_types=1);

namespace Serafim\Bic;

use FFI\CData;
use FFI\CPtr;

interface NativeInterface
{
    /**
     * @return CData|CPtr
     */
    public function getPointer(): CData;
}
