<?php

declare(strict_types=1);

namespace Bic\Tiled\Loader;

use Bic\Tiled\Map;

interface LoaderInterface
{
    /**
     * @param non-empty-string $data
     * @param iterable<non-empty-string> $paths
     * @return Map
     */
    public function load(string $data, iterable $paths = []): Map;
}
