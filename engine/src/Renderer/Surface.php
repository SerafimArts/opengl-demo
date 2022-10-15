<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Bic\Image\ImageInterface;
use Bic\Image\SDL\SDLDecoder;
use FFI\CData;
use FFI\CPtr;
use Serafim\Bic\Native;
use Serafim\SDL\PixelFormat;
use Serafim\SDL\PixelFormatPtr;
use Serafim\SDL\Rect;
use Serafim\SDL\SDL;
use Serafim\SDL\SurfacePtr;
use Serafim\SDL\Image\Image;

/**
 * @method SurfacePtr getPointer()
 * @property-read SurfacePtr $ptr
 */
class Surface extends Native
{
    /**
     * [pixels => surface] references list
     *
     * @var \WeakMap|null
     */
    private static ?\WeakMap $pixels = null;

    /**
     * @param CData $surface
     */
    public function __construct(CData $surface)
    {
        $this->ptr = $surface;

        parent::__construct();
    }

    /**
     * @return CData|Rect
     */
    public function getClipRect(): CData
    {
        return $this->ptr[0]->clip_rect;
    }

    /**
     * @return CData|PixelFormat
     */
    public function getFormat(): CData
    {
        return $this->getFormatPointer()[0];
    }

    /**
     * @return CData|PixelFormatPtr
     */
    public function getFormatPointer(): CData
    {
        return $this->ptr[0]->format;
    }

    /**
     * @param ImageInterface $image
     *
     * @return static
     */
    public static function fromImage(ImageInterface $image): self
    {
        $sdl = SDL::getInstance();

        $format = $image->getFormat();
        $data   = $image->getContents();
        $size   = $image->getBytes();
        $bytes  = $format->getBytesPerPixel();

        /** @var CPtr $pixels */
        \FFI::memcpy($pixels = \FFI::new("int8_t[$size]"), $data, $size);

        $instance = new self($sdl->SDL_CreateRGBSurfaceWithFormatFrom(
            $pixels,
            $image->getWidth(),
            $image->getHeight(),
            $bytes,
            (int)($bytes * $image->getWidth()),
            SDLDecoder::toSDLPixelFormat($image->getFormat()),
        ));

        // Pixels will be available as long as the SDL_Surface lives.
        self::$pixels ??= new \WeakMap();
        self::$pixels[$instance] = $pixels;

        return $instance;
    }

    /**
     * @param string $pathname
     * @return static
     */
    public static function fromPathname(string $pathname): self
    {
        $image = Image::getInstance();

        return new static($image->load($pathname));
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->sdl->SDL_FreeSurface($this->ptr);
    }
}
