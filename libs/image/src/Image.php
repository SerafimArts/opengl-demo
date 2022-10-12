<?php

declare(strict_types=1);

namespace Bic\Image;

final class Image implements ImageInterface
{
    /**
     * @param non-empty-string $contents
     * @param positive-int $width
     * @param positive-int $height
     */
    public function __construct(
        protected readonly Format $format,
        protected readonly int $width,
        protected readonly int $height,
        protected readonly string $contents,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getFormat(): Format
    {
        return $this->format;
    }

    /**
     * {@inheritDoc}
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * {@inheritDoc}
     */
    public function getContents(): string
    {
        return $this->contents;
    }
}
