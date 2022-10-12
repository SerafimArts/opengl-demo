<?php

declare(strict_types=1);

namespace Bic\Stream;

interface RewindableStreamInterface extends StreamInterface
{
    /**
     * Rewind the position of a stream pointer.
     *
     * @return void
     */
    public function rewind(): void;
}
