<?php

declare(strict_types=1);

namespace Bic\Image;

interface FileImageInterface extends ImageInterface
{
    /**
     * @return non-empty-string
     */
    public function getPathname(): string;
}
