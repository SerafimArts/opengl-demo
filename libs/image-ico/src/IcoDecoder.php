<?php

declare(strict_types=1);

namespace Bic\Image\Ico;

use Bic\Binary\Endianness;
use Bic\Binary\StreamInterface;
use Bic\Binary\TypedStream;
use Bic\Image\Ico\Internal\BitMapInfoHeader;
use Bic\Image\Ico\Internal\Compression;
use Bic\Image\Ico\Internal\IcoDirectory;
use Bic\Image\DecoderInterface;
use Bic\Image\Format;
use Bic\Image\Image;
use Bic\Image\ImageInterface;

final class IcoDecoder implements DecoderInterface
{
    /**
     * {@inheritDoc}
     */
    public function decode(StreamInterface $stream): ?iterable
    {
        if ($stream->read(4) === "\x00\x00\x01\x00") {
            return $this->read($stream);
        }

        return null;
    }

    /**
     * @param StreamInterface $stream
     *
     * @return iterable<ImageInterface>
     */
    private function read(StreamInterface $stream): iterable
    {
        $stream = new TypedStream($stream, Endianness::LITTLE);

        /** @var array<IcoDirectory> $directories */
        $directories = [];

        // --- ICO Header ---
        //  - uint16: Always "\x00\x00" (reserved section)
        //  - uint16: Always "\x01\x00" (image type)
        //            The "\x02\x00" header means that image is cursor.
        //  - uint16: Specifies number of images in the file.
        $images = $stream->uint16();

        // Read list of ICO directories
        for ($i = 0; $i < $images; ++$i) {
            $directories[] = $directory = new IcoDirectory(
                width: $stream->int8(),
                height: $stream->int8(),
                colors: $stream->int8(),
                reserved: $stream->int8(),
                colorPlanes: $stream->uint16(),
                bitsPerPixel: $stream->uint16(),
                size: $stream->uint32(),
                offset: $stream->uint32(),
            );

            if ($directory->colors > 0) {
                throw new \LogicException('Indexed colors not supported');
            }
        }

        // Read image data
        foreach ($directories as $ico) {
            // Seek to start of image data section
            $stream->seek($ico->offset);

            // Read BMP Header (40 bytes)
            $info = new BitMapInfoHeader(
                size: $stream->dword(),
                width: $stream->long(),
                height: $stream->long(),
                planes: $stream->word(),
                bitCount: $stream->word(),
                compression: Compression::from($stream->dword()),
                sizeImage: $stream->dword(),
                xPelsPerMeter: $stream->long(),
                yPelsPerMeter: $stream->long(),
                clrUsed: $stream->dword(),
                clrImportant: $stream->dword(),
            );

            // Only RGB images is supported
            if ($info->compression !== Compression::RGB) {
                throw new \LogicException(\vsprintf('Unsupported image compression %s (0x%04X)', [
                    $info->compression->name,
                    $info->compression->value,
                ]));
            }

            // Detect image format
            $format = $this->getFormat($info->bitCount);

            // Read image data
            $data = $info->width >= 0
                ? $this->bottomUp($stream, $ico, $format)
                : $this->topDown($stream, $ico, $format);

            yield new Image($format, $ico->width ?: 256, $ico->height ?: 256, $data);
        }
    }

    /**
     * @return iterable<non-empty-string>
     * @throws \Throwable
     */
    private function lines(TypedStream $stream, IcoDirectory $ico, Format $format): iterable
    {
        $width = $ico->width ?: 256;
        $length = $format->getBytesPerPixel() * $width;

        if (\Fiber::getCurrent()) {
            for ($y = 0, $lines = $ico->height ?: 256; $y < $lines; ++$y) {
                yield $chunk = $stream->read($length);
                \Fiber::suspend($chunk);
            }
        } else {
            for ($y = 0, $lines = $ico->height ?: 256; $y < $lines; ++$y) {
                yield $stream->read($length);
            }
        }
    }

    private function bottomUp(TypedStream $stream, IcoDirectory $ico, Format $format): string
    {
        $result = '';

        foreach ($this->lines($stream, $ico, $format) as $line) {
            $result = $line . $result;
        }

        return $result;
    }

    private function topDown(TypedStream $stream, IcoDirectory $ico, Format $format): string
    {
        $result = '';

        foreach ($this->lines($stream, $ico, $format) as $line) {
            $result .= $line;
        }

        return $result;
    }

    private function getFormat(int $bits): Format
    {
        return match (true) {
            // BGRA
            $bits === 32 => Format::B8G8R8A8,
            // BGR
            $bits === 24 => Format::B8G8R8,
            default => throw new \LogicException(
                \sprintf('Supported only B8G8R8A8 (32bit) or B8G8R8 (24bit), but %d given', $bits)
            ),
        };
    }
}
