<?php

declare(strict_types=1);

namespace Bic\Tiled\Texture;

use Bic\Tiled\Common\Size;

abstract class Texture implements TextureInterface
{
    /**
     * @param Format $format
     * @param Size $size
     */
    public function __construct(
        protected readonly Format $format,
        protected readonly Size $size,
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
        return $this->size->width;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeight(): int
    {
        return $this->size->height;
    }
}
