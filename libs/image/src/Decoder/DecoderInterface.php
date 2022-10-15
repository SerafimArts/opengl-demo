<?php

declare(strict_types=1);

namespace Bic\Image\Decoder;

use Bic\Image\Binary\StreamInterface;
use Bic\Image\ImageInterface;

interface DecoderInterface
{
    /**
     * @param StreamInterface $stream
     *
     * @return iterable<ImageInterface>|null
     */
    public function decode(StreamInterface $stream): ?iterable;
}
