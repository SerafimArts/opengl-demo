<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Binary\FileStream;
use Bic\Image\Exception\ImageException;

class Factory implements FactoryInterface
{
    /**
     * @var list<DecoderInterface>
     */
    private array $decoders = [];

    /**
     * @param list<DecoderInterface>|null $decoders
     */
    public function __construct(iterable $decoders = null)
    {
        foreach ($decoders ?? [] as $decoder) {
            $this->extend($decoder);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function extend(DecoderInterface $decoder): void
    {
        $this->decoders[] = $decoder;
    }

    /**
     * {@inheritDoc}
     */
    public function fromPathname(string $pathname): iterable
    {
        $stream = new FileStream($pathname);

        foreach ($this->decoders as $decoder) {
            $stream->rewind();

            if (\is_iterable($images = $decoder->decode($stream))) {
                foreach ($images as $image) {
                    yield new FileImage($pathname, $image);
                }

                return;
            }
        }

        throw new ImageException('Unsupported image format');
    }
}
