<?php

declare(strict_types=1);

namespace Bic\Image;

interface FactoryInterface
{
    /**
     * @param DecoderInterface $decoder
     *
     * @return void
     */
    public function extend(DecoderInterface $decoder): void;

    /**
     * @psalm-taint-sink file $pathname
     *
     * @param non-empty-string $pathname
     *
     * @return iterable<FileImageInterface>
     */
    public function fromPathname(string $pathname): iterable;
}
