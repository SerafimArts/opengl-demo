<?php

declare(strict_types=1);

namespace Bic\Tiled\Texture;

use Bic\Tiled\Common\Size;

final class FileTexture extends Texture
{
    /**
     * @var int
     */
    private int $sync;

    /**
     * @var non-empty-string|null
     */
    private ?string $content = null;

    /**
     * @psalm-taint-sink file $pathname
     * @param Format $format
     * @param Size $size
     * @param non-empty-string $pathname
     */
    public function __construct(
        Format $format,
        Size $size,
        private readonly string $pathname,
    ) {
        parent::__construct($format, $size);

        $this->sync = \filemtime($this->pathname);
    }

    /**
     * @return non-empty-string
     */
    public function getPathname(): string
    {
        return $this->pathname;
    }

    /**
     * @return bool
     */
    private function isOutdated(): bool
    {
        return $this->sync !== \filemtime($this->pathname);
    }

    /**
     * @return non-empty-string
     */
    public function getContents(): string
    {
        if ($this->content === null || $this->isOutdated()) {
            $this->sync = \filemtime($this->pathname);
            $this->content = \file_get_contents($this->pathname);
        }

        return $this->content;
    }
}
