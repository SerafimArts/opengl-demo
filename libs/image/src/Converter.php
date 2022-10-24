<?php

declare(strict_types=1);

namespace Bic\Image;

final class Converter implements ConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function convert(ImageInterface $image, Format $output): ImageInterface
    {
        if ($image->getFormat() === $output) {
            return $image;
        }

        $input = $image->getFormat();

        $shift = $input->getBytesPerPixel();
        // size of image data (in bytes): WIDTH * HEIGHT * BYTES
        $length = $image->getWidth() * $image->getHeight() * $shift;

        // Input raw (bytes) data payload
        $idata  = $image->getContents();

        // Output raw (bytes) data payload
        $odata = '';

        for ($offset = 0; $offset < $length; $offset += $shift) {
            // Pixel in RGBA input
            $pixel = match ($input) {
                Format::R8G8B8 => $idata[$offset] . $idata[$offset + 1] . $idata[$offset + 2] . "\x00",
                Format::B8G8R8 => $idata[$offset + 2] . $idata[$offset + 1] . $idata[$offset] . "\x00",
                Format::R8G8B8A8 => $idata[$offset] . $idata[$offset + 1] . $idata[$offset + 2] . $idata[$offset + 3],
                Format::B8G8R8A8 => $idata[$offset + 2] . $idata[$offset + 1] . $idata[$offset] . $idata[$offset + 3],
                Format::A8B8G8R8 => $idata[$offset + 3] . $idata[$offset + 2] . $idata[$offset + 1] . $idata[$offset],
                default => throw new \LogicException('Unsupported input ' . $input->name),
            };

            // RGBA to output input
            $odata .= match ($output) {
                Format::R8G8B8 => $pixel[0] . $pixel[1] . $pixel[2],
                Format::B8G8R8 => $pixel[2] . $pixel[1] . $pixel[0],
                Format::R8G8B8A8 => $pixel,
                Format::B8G8R8A8 => $pixel[0] . $pixel[1] . $pixel[2] . $pixel[3],
                Format::A8B8G8R8 => $pixel[3] . $pixel[2] . $pixel[1] . $pixel[0],
            };
        }

        return new Image($output, $image->getWidth(), $image->getHeight(), $odata);
    }
}
