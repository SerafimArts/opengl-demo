<?php

declare(strict_types=1);

namespace Bic\Tiled\Loader;

use Bic\Tiled\Map\Map;
use Bic\Tiled\Texture\RepositoryInterface;

abstract class Loader implements LoaderInterface
{
    /**
     * @param RepositoryInterface $textures
     */
    public function __construct(
        private readonly RepositoryInterface $textures,
    ) {
    }

    public function load(string $data, iterable $paths = []): Map
    {
        $decoded = \json_decode($data, true, flags: \JSON_THROW_ON_ERROR);

        dd($decoded);
    }
}
