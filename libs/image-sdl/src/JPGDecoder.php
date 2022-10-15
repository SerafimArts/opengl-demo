<?php

declare(strict_types=1);

namespace Bic\Image\SDL;

use Bic\Image\Binary\StreamInterface;
use Bic\Image\SDL\Kernel\InitFlags;
use FFI\CData;

class JPGDecoder extends SDLDecoder
{
    /**
     * {@inheritDoc}
     */
    public function __construct(object $sdl, object $image)
    {
        parent::__construct($sdl, $image);

        $this->image->init(InitFlags::IMG_INIT_JPG);
    }

    public function decode(StreamInterface $stream): ?iterable
    {
        if ($stream->read(2) === "\xFF\xD8") {
            $stream->seek(0);

            return $this->readAsImage($stream);
        }

        return null;
    }

    protected function readAsSurface(StreamInterface $stream): CData
    {
        // CData<SDL_RWops>
        $file = $this->load($stream);

        try {
            $surface = $this->image->IMG_LoadJPG_RW(
                $this->image->cast('SDL_RWops*', $file)
            );

            if ($surface === null) {
                throw new \RuntimeException($this->sdl->SDL_GetError());
            }

            return $this->sdl->cast('SDL_Surface*', $surface);
        } finally {
            $this->sdl->SDL_FreeRW($file);
        }
    }
}
