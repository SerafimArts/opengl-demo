<?php

declare(strict_types=1);

namespace Bic\Tiled\Texture;

interface TextureInterface
{
    /**
     * @return Format
     */
    public function getFormat(): Format;

    /**
     * @return positive-int
     */
    public function getWidth(): int;

    /**
     * @return positive-int
     */
    public function getHeight(): int;

    /**
     * @return non-empty-string
     */
    public function getContents(): string;
}
