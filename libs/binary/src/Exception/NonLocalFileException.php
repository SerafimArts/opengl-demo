<?php

declare(strict_types=1);

namespace Bic\Binary\Exception;

class NonLocalFileException extends StreamException
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     *
     * @return self
     */
    public static function fromPathname(string $pathname): self
    {
        $pathname = \realpath($pathname) ?: $pathname;
        $message = \sprintf('Failed to read "%s" because file is not local', $pathname);

        return new self($message);
    }
}
