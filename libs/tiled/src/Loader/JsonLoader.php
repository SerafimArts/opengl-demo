<?php

declare(strict_types=1);

namespace Bic\Tiled\Loader;

use Bic\Tiled\Map\Map;

final class JsonLoader extends Loader
{
    /**
     * @param string $data
     * @param iterable<non-empty-string> $paths
     * @return Map
     * @throws \JsonException
     */
    public function load(string $data, iterable $paths = []): Map
    {
        $decoded = \json_decode($data, true, flags: \JSON_THROW_ON_ERROR);

        return $this->mapFromArray($decoded);
    }
}
