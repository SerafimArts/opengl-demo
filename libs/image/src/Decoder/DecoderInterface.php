<?php

declare(strict_types=1);

namespace Bic\Image\Decoder;

use Bic\Image\ImageInterface;
use Bic\Image\Stream\StreamInterface;

interface DecoderInterface
{
    /**
     * @param StreamInterface $stream
     *
     * @return bool
     */
    public function match(StreamInterface $stream): bool;

    /**
     * @param StreamInterface $stream
     *
     * @return iterable<ImageInterface>
     */
    public function decode(StreamInterface $stream): iterable;
}
