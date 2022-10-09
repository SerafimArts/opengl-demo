<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Serafim\SDL\SDL;
use Serafim\SDL\SDLNativeApiAutocomplete;

final class Driver
{
    /**
     * @var int
     */
    public int $index;

    /**
     * @var string
     */
    public string $name;

    /**
     * @param int $index
     * @param string $name
     */
    public function __construct(int $index, string $name)
    {
        $this->index = $index;
        $this->name = $name;
    }

    /**
     * @return Driver
     */
    public static function current(): Driver
    {
        /** @var SDLNativeApiAutocomplete $sdl */
        $sdl = SDL::getInstance();

        $driver = self::findByName($sdl->SDL_GetCurrentVideoDriver());

        if ($driver === null) {
            throw new \LogicException('There is no default video driver');
        }

        return $driver;
    }

    /**
     * @param int $id
     * @return Driver|null
     */
    public static function findById(int $id): ?Driver
    {
        foreach (self::all() as $driver) {
            if ($driver->index === $id) {
                return $driver;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return Driver|null
     */
    public static function findByName(string $name): ?Driver
    {
        foreach (self::all() as $driver) {
            if ($driver->name === $name) {
                return $driver;
            }
        }

        return null;
    }

    /**
     * @return iterable|Driver[]
     */
    public static function all(): iterable
    {
        /** @var SDLNativeApiAutocomplete $sdl */
        $sdl = SDL::getInstance();

        for ($i = 0, $len = $sdl->SDL_GetNumVideoDrivers(); $i < $len; ++$i) {
            yield new Driver($i, $sdl->SDL_GetVideoDriver($i));
        }
    }
}
