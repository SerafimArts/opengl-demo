<?php

declare(strict_types=1);

namespace Bic\Image\Stream;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
interface StreamInterface
{
    /**
     * @param positive-int|0 $bytes
     *
     * @return string
     */
    public function read(int $bytes): string;

    /**
     * Absolute stream offset movement.
     *
     * @param positive-int|0 $offset
     *
     * @return positive-int|0
     */
    public function seek(int $offset): int;

    /**
     * Relative stream offset movement.
     *
     * @param int $offset
     *
     * @return positive-int|0
     */
    public function move(int $offset): int;

    /**
     * Returns current offset of the stream.
     *
     * @return positive-int|0
     */
    public function offset(): int;
}
