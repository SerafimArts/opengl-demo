<?php

declare(strict_types=1);

namespace Bic\Image;

final class FileImage implements FileImageInterface
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     */
    public function __construct(
        private readonly string $pathname,
        private readonly ImageInterface $icon,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getFormat(): Format
    {
        return $this->icon->getFormat();
    }

    /**
     * {@inheritDoc}
     */
    public function getWidth(): int
    {
        return $this->icon->getWidth();
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight(): int
    {
        return $this->icon->getHeight();
    }

    /**
     * {@inheritDoc}
     */
    public function getContents(): string
    {
        return $this->icon->getContents();
    }

    /**
     * {@inheritDoc}
     */
    public function getPathname(): string
    {
        return $this->pathname;
    }
}
