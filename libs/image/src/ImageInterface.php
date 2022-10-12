<?php

declare(strict_types=1);

namespace Bic\Image;

interface ImageInterface
{
    /**
     * @return Format
     */
    public function getFormat(): Format;

    /**
     * @psalm-return positive-int|0
     */
    public function getWidth(): int;

    /**
     * @psalm-return positive-int|0
     */
    public function getHeight(): int;

    /**
     * @return non-empty-string
     */
    public function getContents(): string;
}
