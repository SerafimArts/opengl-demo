<?php

declare(strict_types=1);

namespace Bic\Image\Stream;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
class ResourceStream implements StreamInterface
{
    /**
     * @param resource $stream
     * @param bool $close
     */
    public function __construct(
        private readonly mixed $stream,
        private readonly bool $close = false,
    ) {
    }

    /**
     * @param string $string
     *
     * @return static
     */
    public static function fromString(string $string): self
    {
        $stream = \fopen('php://memory','r+');
        \fwrite($stream, $string);
        \rewind($stream);

        return new self($stream, true);
    }

    /**
     * {@inheritDoc}
     */
    public function read(int $bytes): string
    {
        assert($bytes > 0);

        $result = \fread($this->stream, $bytes);

        return $result . \str_repeat("\x00", $bytes - \strlen($result));
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
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function offset(): int
    {
        return (int)\ftell($this->stream);
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
