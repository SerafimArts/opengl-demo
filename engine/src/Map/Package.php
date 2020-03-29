<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Map;

use App\Game;
use Illuminate\Support\Str;
use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\Bic\Renderer\Texture;

/**
 * Class Package
 */
class Package
{
    /**
     * @var string
     */
    public const MAP_NAME = 'map.json';

    /**
     * @var string
     */
    private string $pathname;

    /**
     * Package constructor.
     *
     * @param string $pathname
     */
    public function __construct(string $pathname)
    {
        $this->pathname = $pathname;
    }

    /**
     * @param Game $game
     * @param string $json
     * @return void
     */
    public function build(Game $game, string $json): void
    {
        $data = Data::fromJson($game, $json);

        $phar = new \Phar($this->pathname . '.phar', 0);

        $images = [];
        $map = $data->all();

        foreach ($map['tilesets'] ?? [] as $i => $tiles) {
            $images[$tiles['image']] = Str::lower(Str::random(32)) . '.png';

            $map['tilesets'][$i]['image'] = $images[$tiles['image']];
        }

        $phar->addFromString(self::MAP_NAME, \json_encode($map, \JSON_THROW_ON_ERROR));

        foreach ($images as $src => $target) {
            $phar->addFile(\dirname($json) . '/' . $src, $target);
        }

        if (\is_file($this->pathname)) {
            \unlink($this->pathname);
        }

        \rename($this->pathname . '.phar', $this->pathname);
    }

    /**
     * @param Lifecycle $game
     * @return Data
     */
    public function read(Lifecycle $game): Data
    {
        $alias = Str::lower(Str::random(32)) . '.phar';

        \Phar::loadPhar($this->pathname, $alias);

        $data = $textures = [];

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \Phar($alias)) as $file) {
            if ($file->getBasename() === self::MAP_NAME) {
                $sources = \file_get_contents($file->getPathname());
                $data = \json_decode($sources, true, 512, \JSON_THROW_ON_ERROR);
            } else {
                $textures[$file->getBasename()] = $this->createTexture($game, $file);
            }
        }

        return new Data($data, $textures);
    }

    /**
     * @param Lifecycle $game
     * @param \SplFileInfo $source
     * @return Texture
     */
    private function createTexture(Lifecycle $game, \SplFileInfo $source): Texture
    {
        $storage = $game->app->storagePath($source->getBasename());

        \copy($source->getPathname(), $storage);

        try {
            return Texture::fromPathname($game->renderer, $storage);
        } finally {
            \unlink($storage);
        }
    }
}
