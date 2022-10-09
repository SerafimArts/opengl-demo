<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Serafim\SDL\SDL;
use Serafim\SDL\Image\Image;
use Serafim\SDL\SDLNativeApiAutocomplete;

class DirectMediaServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        /** @var SDLNativeApiAutocomplete $sdl */
        $sdl = SDL::getInstance();
        $sdl->SDL_Init(SDL::SDL_INIT_VIDEO | SDL::SDL_INIT_AUDIO);

        $this->app->instance(SDL::class, $sdl);

        $image = Image::getInstance();
        $image->init(Image::IMG_INIT_PNG);
        $this->app->instance(Image::class, $image);
    }
}
