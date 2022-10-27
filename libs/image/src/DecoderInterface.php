<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Binary\StreamInterface;

interface DecoderInterface
{
    /**
     * @param StreamInterface $stream
     *
     * @return iterable<ImageInterface>|null
     */
    public function decode(StreamInterface $stream): ?iterable;
}
