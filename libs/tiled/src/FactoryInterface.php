<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Map\Map;

interface FactoryInterface
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     * @param Format|null $format
     * @return Map
     */
    public function fromPathname(string $pathname, Format $format = null): Map;
}
