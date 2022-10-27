<?php

declare(strict_types=1);

namespace Bic\Binary\Exception;

class FileNotFoundException extends StreamException
{
    public static function fromPathname(string $pathname): self
    {
        $pathname = \realpath($pathname) ?: $pathname;
        $message = \sprintf('Failed to read "%s" because file does not exist', $pathname);

        return new self($message);
    }
}
