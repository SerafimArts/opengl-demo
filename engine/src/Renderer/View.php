<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Bic\Image\FactoryInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\SDL\SDLNativeApiAutocomplete;
use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\SDL\SDL;

/**
 * Class View
 *
 * @method void load()
 */
abstract class View implements ViewInterface
{
    /**
     * @var SDL|SDLNativeApiAutocomplete
     */
    protected readonly SDL $sdl;

    /**
     * @var Lifecycle
     */
    protected readonly Lifecycle $game;

    /**
     * @var FactoryInterface
     */
    protected readonly FactoryInterface $images;

    /**
     * @param Lifecycle $game
     *
     * @throws BindingResolutionException
*/
    public function __construct(Lifecycle $game)
    {
        $this->sdl = SDL::getInstance();
        $this->game = $game;
        $this->images = $game->app->make(FactoryInterface::class);

        if (\method_exists($this, 'load')) {
            $game->app->call(\Closure::fromCallable([$this, 'load']));
        }
    }

    /**
     * @param string $pathname
     * @return Texture
     */
    protected function texture(string $pathname): Texture
    {
        if (! \is_file($pathname)) {
            $pathname = $this->game->app->resourcesPath($pathname);
        }

        return Texture::fromPathname($this->game->renderer, $pathname);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function resources(string $path): string
    {
        return $this->game->app->resourcesPath($path);
    }
}
