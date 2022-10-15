<?php

declare(strict_types=1);

namespace App\Providers;

use Bic\Image\Factory;
use Bic\Image\FactoryInterface;
use Bic\Image\SDL\JPGDecoder;
use Bic\Image\SDL\PNGDecoder;
use Serafim\Bic\Application\ServiceProvider;
use Serafim\SDL\Image\Image;
use Serafim\SDL\SDL;

class ImageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Factory::class, function (): FactoryInterface {
            $sdl = $this->app->make(SDL::class);
            $image = $this->app->make(Image::class);

            $instance = new Factory();
            $instance->extend(new JPGDecoder($sdl, $image));
            $instance->extend(new PNGDecoder($sdl, $image));

            return $instance;
        });

        $this->app->alias(Factory::class, FactoryInterface::class);
    }
}
