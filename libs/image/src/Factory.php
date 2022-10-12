<?php

declare(strict_types=1);

namespace Bic\Image;

use Bic\Image\Exception\ImageException;
use Bic\Image\Stream\ResourceStream;
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
        if (!$this->isLocal($pathname)) {
            $message = \sprintf('Failed to read "%s" because file is not local', $pathname);
            throw new InvalidArgumentException($message);
        }

        if (!$this->isFile($pathname)) {
            $message = \sprintf('Failed to read "%s" because file does not exist', $pathname);
            throw new InvalidArgumentException($message);
        }

        if (!$this->isReadable($pathname)) {
            $message = \sprintf('Failed to read "%s" because file could not be opened for reading', $pathname);
            throw new InvalidArgumentException($message);
        }

        $stream = new ResourceStream(\fopen($pathname, 'rb'));

        foreach ($this->decoders as $decoder) {
            if ($decoder->match($stream)) {
                foreach ($decoder->decode($stream) as $image) {
                    yield new FileImage($pathname, $image);
                }

                return;
            }

            $stream->seek(0);
        }

        throw new ImageException('Unsupported image format');
    }

    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     */
    private function isLocal(string $pathname): bool
    {
        return \stream_is_local($pathname) || \str_starts_with($pathname, 'file://');
    }

    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     */
    private function isFile(string $pathname): bool
    {
        return \is_file($pathname);
    }

    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     */
    private function isReadable(string $pathname): bool
    {
        return \is_readable($pathname);
    }
}
