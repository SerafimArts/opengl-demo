<?php

declare(strict_types=1);

namespace Bic\Tiled;

use Bic\Tiled\Texture\FilesystemRepository;
use Bic\Tiled\Texture\RepositoryInterface;

final class Factory implements FactoryInterface
{
    /**
     * @var RepositoryInterface
     */
    private readonly RepositoryInterface $textures;

    public function __construct()
    {
        $this->textures = new FilesystemRepository();
    }

    public function fromPathname(string $pathname, Format $format): Map
    {
        $loader = match ($format) {
            Format::TILED_JSON
        };
    }
}
