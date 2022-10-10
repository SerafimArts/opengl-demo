<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Bic\UI\SDL\Window;
use FFI\CData;
use Serafim\Bic\Native;
use Serafim\SDL\PixelFormat;
use Serafim\SDL\PixelFormatPtr;
use Serafim\SDL\Rect;
use Serafim\SDL\SurfacePtr;
use Serafim\SDL\Image\Image;

/**
 * @method SurfacePtr getPointer()
 * @property-read SurfacePtr $ptr
 */
class Surface extends Native
{
    /**
     * @param CData $surface
     */
    public function __construct(CData $surface)
    {
        $this->ptr = $surface;

        parent::__construct();
    }

    /**
     * @param Window $window
     * @return void
     */
    public function convert(Window $window): void
    {
        $before = $this->ptr;

        $cdata = $this->sdl->cast('SDL_Window*', $window->getCData());
        $format = $this->sdl->SDL_GetWindowPixelFormat($cdata);

        $this->ptr = $this->sdl->SDL_ConvertSurfaceFormat($before, $format, 0);

        $this->sdl->SDL_FreeSurface($before);
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
