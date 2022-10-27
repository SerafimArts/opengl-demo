<?php

declare(strict_types=1);

namespace Bic\Binary;

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
     * Rewind the position of a stream pointer.
     *
     * @return void
     */
    public function rewind(): void;

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

    /**
     * Returns {@see true} in case of stream is ends or {@see false} instead.
     *
     * @return bool
     */
    public function isCompleted(): bool;
}
