<?php

declare(strict_types=1);

namespace Bic\Tiled\Texture;

use Bic\Tiled\Common\Size;

final class FilesystemRepository implements RepositoryInterface
{
    /**
     * @var array<non-empty-string, TextureInterface>
     */
    private array $map = [];

    /**
     * {@inheritDoc}
     */
    public function has(string $name): bool
    {
        return \is_file($name);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $name): TextureInterface
    {
        if (\is_file($name)) {
            $name = \realpath($name);

            return $this->map[$name] ??= $this->load($name);
        }

        throw new \InvalidArgumentException('Could not load [' . $name . '] texture');
    }

    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     * @return TextureInterface
     */
    private function load(string $pathname): TextureInterface
    {
        $stream = \fopen($pathname, 'rb');

        $format = $this->detectFormat($stream);

        return new FileTexture($format, match ($format) {
            Format::PNG => $this->detectPNGSize($stream),
        }, $pathname);
    }

    /**
     * @link https://www.w3.org/TR/PNG-Structure.html
     * @param mixed $stream
     * @return Size
     */
    private function detectPNGSize(mixed $stream): Size
    {
        // int8 x 8 (8b) - PNG Signature
        // uint32   (4b) - Length
        \fseek($stream, 8 + 4);

        // int8 x 4 (4b) - Chunk Type
        if (($ctype = \fread($stream, 4)) !== 'IHDR') {
            throw new \InvalidArgumentException(
                \sprintf('Unsupported PNG chunk type "%s"', $ctype)
            );
        }

        // uint32 (4b) - Width
        // uint32 (4b) - Height
        $size = \fread($stream, 4 + 4);

        if (\strlen($size) !== 8) {
            throw new \InvalidArgumentException('Failed to correctly read PNG sizes');
        }

        ['w' => $width, 'h' => $height] = \unpack('Nw/Nh', $size);

        return new Size($width, $height);
    }

    /**
     * @param resource $stream
     * @return Format
     */
    private function detectFormat(mixed $stream): Format
    {
        return match (true) {
            $this->match($stream, "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") => Format::PNG,
            default => throw new \InvalidArgumentException(
                'Unsupported image file format'
            ),
        };
    }

    /**
     * @param resource $stream
     * @param non-empty-string $signature
     * @return bool
     */
    private function match(mixed $stream, string $signature): bool
    {
        \rewind($stream);

        return \fread($stream, \strlen($signature)) === $signature;
    }
}
