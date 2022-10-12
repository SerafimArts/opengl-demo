<?php

declare(strict_types=1);

namespace Bic\Image;

interface ConverterInterface
{
    /**
     * @param ImageInterface $image
     * @param Format $output
     *
     * @return ImageInterface
     */
    public function convert(ImageInterface $image, Format $output): ImageInterface;
}
