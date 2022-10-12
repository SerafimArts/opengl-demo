<?php

declare(strict_types=1);

namespace Bic\Image\Stream;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image
 */
class StringStream implements StreamInterface
{
    /**
     * @var positive-int|0
     */
    private int $offset = 0;

    /**
     * @param string $data
     */
    public function __construct(
        private readonly string $data,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function read(int $bytes): string
    {
        assert($bytes > 0);

        $result = \substr($this->data, $this->offset, $bytes);
        $this->offset += $bytes;

        return $result . \str_repeat("\x00", $bytes - \strlen($result));
    }

    /**
     * {@inheritDoc}
     */
    public function seek(int $offset): int
    {
        return $this->offset = $offset;
    }

    /**
     * {@inheritDoc}
     */
    public function move(int $offset): int
    {
        return $this->offset += $offset;
    }

    /**
     * {@inheritDoc}
     */
    public function offset(): int
    {
        return $this->offset;
    }
}
