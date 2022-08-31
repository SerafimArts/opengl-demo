<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Loader\JsonLoader;
use Bic\Tiled\Loader\LoaderInterface;
use Bic\Tiled\Loader\TmxLoader;
use Bic\Tiled\Map\Map;

final class Factory implements FactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const ERROR_FORMAT = 'Unsupported map format';

    /**
     * {@inheritDoc}
     */
    public function fromPathname(string $pathname, Format $format = null): Map
    {
        $data = \file_get_contents($pathname);

        $loader = $this->getLoader($format ?? $this->detectFormat($data));

        return $loader->load($data, [\dirname($pathname)]);
    }

    /**
     * @param Format $format
     * @return LoaderInterface
     */
    private function getLoader(Format $format): LoaderInterface
    {
        return match ($format) {
            Format::JSON => new JsonLoader(),
            Format::TMX => new TmxLoader(),
            default => throw new \InvalidArgumentException(self::ERROR_FORMAT)
        };
    }

    /**
     * @param string $data
     * @return Format
     */
    private function detectFormat(string $data): Format
    {
        $data = \trim($data);

        return match ($data[0] ?? '') {
            '{' => Format::JSON,
            '<' => Format::TMX,
            default => throw new \InvalidArgumentException(self::ERROR_FORMAT)
        };
    }
}
