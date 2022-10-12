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

        if ($input->getBitsPerPixel() % 8) {
            throw new \LogicException('Unsupported bits per pixel');
        }

        // bytes per pixel: BITS / 8
        $shift  = (int)($input->getBitsPerPixel() / 8);
        // size of image data (in bytes): WIDTH * HEIGHT * BYTES
        $length = $image->getWidth() * $image->getHeight() * $shift;

        $inputData  = $image->getContents();
        $outputData = '';

        for ($offset = 0; $offset < $length; $offset += $shift) {
            // Pixel in RGBA input
            $pixel = match ($input) {
                Format::R8G8B8 => $inputData[$offset] . $inputData[$offset + 1] . $inputData[$offset + 2] . "\x00",
                Format::B8G8R8 => $inputData[$offset + 2] . $inputData[$offset + 1] . $inputData[$offset] . "\x00",
                Format::R8G8B8A8 => $inputData[$offset] . $inputData[$offset + 1] . $inputData[$offset + 2] . $inputData[$offset + 3],
                Format::B8G8R8A8 => $inputData[$offset + 2] . $inputData[$offset + 1] . $inputData[$offset] . $inputData[$offset + 3],
                Format::A8B8G8R8 => $inputData[$offset + 3] . $inputData[$offset + 2] . $inputData[$offset + 1] . $inputData[$offset],
                default => throw new \LogicException('Unsupported input ' . $input->name),
            };

            // RGBA to output input
            $outputData .= match ($output) {
                Format::R8G8B8 => $pixel[0] . $pixel[1] . $pixel[2],
                Format::B8G8R8 => $pixel[2] . $pixel[1] . $pixel[0],
                Format::R8G8B8A8 => $pixel,
                Format::B8G8R8A8 => $pixel[0] . $pixel[1] . $pixel[2] . $pixel[3],
                Format::A8B8G8R8 => $pixel[3] . $pixel[2] . $pixel[1] . $pixel[0],
            };
        }

        return new Image($output, $image->getWidth(), $image->getHeight(), $outputData);
    }
}
