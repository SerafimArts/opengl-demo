<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

use Bic\Tiled\Common\Version;

final class TiledVersion extends Version
{
    /**
     * @return self
     */
    public static function latest(): self
    {
        return new self(1, 9);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->toMajorMinorPatchString();
    }
}
