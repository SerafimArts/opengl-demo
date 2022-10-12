<?php

declare(strict_types=1);

namespace Bic\Image\Decoder;

use Bic\Image\ImageInterface;
use Bic\Stream\StreamInterface;

interface DecoderInterface
{
    /**
     * @param StreamInterface $stream
     *
     * @return iterable<ImageInterface>|null
     */
    public function decode(StreamInterface $stream): ?iterable;
}
