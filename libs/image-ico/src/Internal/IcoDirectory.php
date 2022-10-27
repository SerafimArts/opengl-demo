<?php

declare(strict_types=1);

namespace Bic\Image\Ico\Internal;

/**
 * Icon Entry info (16 bytes)
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\Image\Ico
 */
final class IcoDirectory
{
    /**
     * @param positive-int $width Specifies image width in pixels. Can be any
     *                            number between 0 and 255. Value 0 means
     *                            image width is 256 pixels.
     * @param positive-int $height Specifies image height in pixels. Can be
     *                             any number between 0 and 255. Value 0
     *                             means image height is 256 pixels.
     * @param positive-int $colors Specifies number of colors in the color
     *                             palette. Should be 0 if the image does not
     *                             use a color palette.
     * @param positive-int $reserved Reserved. Should be 0.
     * @param positive-int $colorPlanes Specifies color planes. Should be 0 or 1.
     * @param positive-int $bitsPerPixel Specifies bits per pixel.
     * @param positive-int $size Specifies the size of the image's data in bytes.
     * @param positive-int $offset Specifies the offset of BMP or PNG data from
     *                             the beginning of the ICO/CUR file.
     */
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly int $colors,
        public readonly int $reserved,
        public readonly int $colorPlanes,
        public readonly int $bitsPerPixel,
        public readonly int $size,
        public readonly int $offset,
    ) {
        assert($this->width >= 0 && $this->width <= 256);
        assert($this->height >= 0 && $this->height <= 256);
        assert($this->colors >= 0 && $this->colors <= 256);
        assert($this->bitsPerPixel >= 0);
        assert($this->bitsPerPixel % 8 === 0);
        assert($this->size >= 0);
        assert($this->offset >= 0);
    }
}
