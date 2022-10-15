<?php

declare(strict_types=1);

namespace Bic\Image\SDL;

use Bic\Image\Binary\Endianness;
use Bic\Image\Binary\StreamInterface;
use Bic\Image\Decoder\DecoderInterface;
use Bic\Image\Format;
use Bic\Image\Image;
use Bic\Image\SDL\Kernel\PixelFormat;
use FFI\CData;

abstract class SDLDecoder implements DecoderInterface
{
    /**
     * @param object|\FFI $sdl
     * @param object|\FFI $image
     */
    public function __construct(
        protected readonly object $sdl,
        protected readonly object $image,
    ) {
    }

    /**
     * @return array<int, Format>
     */
    public static function getFormatMappings(): array
    {
        static $formats = null;

        if ($formats !== null) {
            return $formats;
        }

        if (Endianness::auto() === Endianness::LITTLE) {
            return $formats = [
                // Doesnt works correctly ='(
                // PixelFormat::SDL_PIXELFORMAT_BGR888 => Format::R8G8B8,
                // PixelFormat::SDL_PIXELFORMAT_RGB888 => Format::B8G8R8,
                PixelFormat::SDL_PIXELFORMAT_ABGR8888 => Format::R8G8B8A8,
                PixelFormat::SDL_PIXELFORMAT_ARGB8888 => Format::B8G8R8A8,
                PixelFormat::SDL_PIXELFORMAT_BGRA8888 => Format::A8B8G8R8,
            ];
        }

        return $formats = [
            // Doesnt works correctly ='(
            // PixelFormat::SDL_PIXELFORMAT_RGB888 => Format::R8G8B8,
            // PixelFormat::SDL_PIXELFORMAT_BGR888 => Format::B8G8R8,
            PixelFormat::SDL_PIXELFORMAT_RGBA8888 => Format::R8G8B8A8,
            PixelFormat::SDL_PIXELFORMAT_BGRA8888 => Format::B8G8R8A8,
            PixelFormat::SDL_PIXELFORMAT_ARGB8888 => Format::A8B8G8R8,
        ];
    }

    /**
     * @param Format $format
     *
     * @return int|null
     */
    public static function toSDLPixelFormat(Format $format): ?int
    {
        $key = \array_search($format, self::getFormatMappings());

        if ($key === false) {
            return null;
        }

        return (int)$key;
    }

    /**
     * @param int $pixelFormat
     *
     * @return Format|null
     */
    public static function fromSDLPixelFormat(int $pixelFormat): ?Format
    {
        $mapping = self::getFormatMappings();

        return $mapping[$pixelFormat] ?? null;
    }

    /**
     * @param StreamInterface $stream
     *
     * @return CData SDL_Surface*
     */
    abstract protected function readAsSurface(StreamInterface $stream): CData;

    /**
     * @param StreamInterface $stream
     *
     * @return iterable<Image>
     */
    protected function readAsImage(StreamInterface $stream): iterable
    {
        $surface = $this->convertSurfaceFormatToSupported(
            $this->readAsSurface($stream),
        );

        try {
            $format = $this->getSurfaceFormat($surface);

            return [new Image(
                format: $format,
                width: $surface->w,
                height: $surface->h,
                contents: $this->getPixelsFromSurface($surface),
            )];
        } finally {
            $this->sdl->SDL_FreeSurface($surface);
        }
    }

    /**
     * @param CData $surface
     *
     * @return CData
     */
    protected function convertSurfaceFormatToSupported(CData $surface): CData
    {
        if ($this->isSurfaceFormatSupported($surface)) {
            return $surface;
        }

        try {
            $previous = $surface;
            $expected = self::toSDLPixelFormat(Format::R8G8B8A8);

            return $this->sdl->SDL_ConvertSurfaceFormat($previous, $expected, 0);
        } finally {
            $this->sdl->SDL_FreeSurface($previous);
        }
    }

    /**
     * @param CData $surface
     *
     * @return Format
     */
    protected function getSurfaceFormat(CData $surface): Format
    {
        return $this->findSurfaceFormat($surface)
            ?? throw new \LogicException('Unsupported surface format [' . $surface->format->format . ']');
    }

    /**
     * @param CData $surface
     *
     * @return Format|null
     */
    protected function findSurfaceFormat(CData $surface): ?Format
    {
        return self::fromSDLPixelFormat($surface->format->format);
    }

    /**
     * @param CData $surface
     *
     * @return bool
     */
    protected function isSurfaceFormatSupported(CData $surface): bool
    {
        return $this->findSurfaceFormat($surface) !== null;
    }

    /**
     * @param CData $surface
     *
     * @return string
     */
    protected function getPixelsFromSurface(CData $surface): string
    {
        $size = $surface->pitch * $surface->w;

        return \FFI::string($surface->pixels, $size);
    }

    /**
     * @param StreamInterface $stream
     *
     * @return CData
     * @throws \Throwable
     */
    protected function load(StreamInterface $stream): CData
    {
        $memory = '';

        if (\Fiber::getCurrent()) {
            while (!$stream->isCompleted()) {
                \Fiber::suspend($chunk = $stream->read(1024));
                $memory .= $chunk;
            }
        } else {
            while (!$stream->isCompleted()) {
                $memory .= $stream->read(1024 * 8);
            }
        }

        $size = \strlen($memory);

        $cdata = \FFI::new("uint8_t[$size]");
        \FFI::memcpy($cdata, $memory, $size);

        return $this->sdl->SDL_RWFromMem($cdata, $size);
    }
}
