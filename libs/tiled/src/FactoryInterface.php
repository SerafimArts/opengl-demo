<?php

declare(strict_types=1);

namespace Bic\Tiled;

interface FactoryInterface
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     * @param Format $format
     * @return Map
     */
    public function fromPathname(string $pathname, Format $format): Map;
}
