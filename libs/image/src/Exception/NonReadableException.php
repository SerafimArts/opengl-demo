<?php

declare(strict_types=1);

namespace Bic\Image\Exception;

class NonReadableException extends StreamException
{
    public static function fromPathname(string $pathname): self
    {
        $pathname = \realpath($pathname) ?: $pathname;
        $message = \sprintf('Failed to read "%s" because file could not be opened for reading', $pathname);

        return new self($message);
    }
}
