<?php

declare(strict_types=1);

namespace Bic\Audio;

use Bic\Audio\Repository\FileSourceInterface;

interface RepositoryInterface
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     * @param positive-int|0 $offset
     * @param positive-int|null $length
     * @return FileSourceInterface
     */
    public function loadFromPathname(string $pathname, int $offset = 0, int $length = null): FileSourceInterface;
}
