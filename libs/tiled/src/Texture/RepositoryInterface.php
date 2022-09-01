<?php

declare(strict_types=1);

namespace Bic\Tiled\Texture;

interface RepositoryInterface
{
    /**
     * @param non-empty-string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param non-empty-string $name
     * @return TextureInterface
     */
    public function get(string $name): TextureInterface;
}
