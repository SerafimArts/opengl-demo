<?php

declare(strict_types=1);

namespace Serafim\Bic\Map;

use Illuminate\Config\Repository;
use Serafim\Bic\Lifecycle\Lifecycle;
use Serafim\Bic\Renderer\Texture;

class Data extends Repository
{
    /**
     * @var array|Texture[]
     */
    private array $textures;

    /**
     * @param array $items
     * @param array|Texture[] $textures
     */
    public function __construct(array $items = [], array $textures = [])
    {
        parent::__construct($items);

        $this->textures = $textures;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function detach(string $key): self
    {
        return new static((array)$this->get($key), $this->textures);
    }

    /**
     * @param int $id
     * @return array
     */
    public function layer(int $id): array
    {
        return $this->get('layers.' . $id, []);
    }

    /**
     * @param string $name
     * @return Texture
     */
    public function texture(string $name): Texture
    {
        return $this->textures[$name];
    }

    /**
     * @param string $file
     * @return array
     */
    public static function read(string $file): array
    {
        return \json_decode(\file_get_contents($file), true, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param Lifecycle $game
     * @param string $file
     * @return static
     */
    public static function fromJson(Lifecycle $game, string $file): self
    {
        $data = self::read($file);

        $textures = [];

        foreach ($data['tilesets'] ?? [] as $index => $tiles) {
            $image = $data['tilesets'][$index]['image'];

            $texture = Texture::fromPathname($game->renderer, \dirname($file) . '/' . $image);

            $textures[$image] = $texture;
        }

        return new static($data, $textures);
    }
}
