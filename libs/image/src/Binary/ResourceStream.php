<?php

declare(strict_types=1);

namespace Bic\Image\Binary;

use Bic\Image\Exception\NonReadableException;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
class ResourceStream implements StreamInterface
{
    /**
     * @param resource $stream
     * @param bool $close
     *
     * @throws NonReadableException
     */
    public function __construct(
        private readonly mixed $stream,
        private readonly bool $close = false,
    ) {
        if (!$this->isValidStream($this->stream)) {
            throw new NonReadableException('Could not open non-stream resource for reading');
        }

        if (!$this->isReadable($this->stream)) {
            throw new NonReadableException('Could not open non-readable stream for reading');
        }
    }

    private function isValidStream(mixed $stream): bool
    {
        return \is_resource($stream) && \get_resource_type($stream) === 'stream';
    }

    private function isReadable(mixed $stream): bool
    {
        $meta = \stream_get_meta_data($stream);
        $mode = $meta['mode'];

        return \str_contains($mode, 'r') || \str_contains($mode, '+');
    }

    /**
     * {@inheritDoc}
     */
    public function read(int $bytes): string
    {
        assert($bytes > 0);

        return \fread($this->stream, $bytes);
    }

    /**
     * {@inheritDoc}
     */
    public function seek(int $offset): int
    {
        return \fseek($this->stream, $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function move(int $offset): int
    {
        return \fseek($this->stream, $offset, \SEEK_CUR);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        \rewind($this->stream);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function offset(): int
    {
        return (int)\ftell($this->stream);
    }

    /**
     * {@inheritDoc}
     */
    public function isCompleted(): bool
    {
        return \feof($this->stream);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if ($this->close) {
            \fclose($this->stream);
        }
    }
}
