<?php

declare(strict_types=1);

namespace Bic\Audio\Repository;

interface FileSourceInterface extends SourceInterface
{
    /**
     * @return non-empty-string
     */
    public function getPathname(): string;
}
