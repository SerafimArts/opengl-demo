<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Image\Binary\FileStream;
use Bic\Image\Exception\ImageException;
use Bic\Image\Exception\InvalidArgumentException;
use Bic\Image\Decoder\DecoderInterface;
use Bic\Image\Decoder\IcoDecoder;

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
        foreach ($decoders ?? $this->getDefaultDecoders() as $decoder) {
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
     * @return non-empty-list<DecoderInterface>
     */
    private function getDefaultDecoders(): iterable
    {
        yield new IcoDecoder();
    }

    /**
     * {@inheritDoc}
     *
     * @throws ImageException
     * @throws InvalidArgumentException
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
