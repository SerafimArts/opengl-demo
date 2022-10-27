<?php

declare(strict_types=1);

namespace Bic\Binary;

use Bic\Binary\Exception\FileNotFoundException;
use Bic\Binary\Exception\NonLocalFileException;
use Bic\Binary\Exception\NonReadableException;
use Bic\Binary\Exception\StreamException;

class FileStream extends ResourceStream
{
    /**
     * @psalm-taint-sink file $pathname
     * @param non-empty-string $pathname
     *
     * @throws StreamException
     */
    public function __construct(string $pathname)
    {
        if (!$this->isLocal($pathname)) {
            throw NonLocalFileException::fromPathname($pathname);
        }

        if (!$this->isFile($pathname)) {
            throw FileNotFoundException::fromPathname($pathname);
        }

        if (!$this->isReadable($pathname)) {
            throw NonReadableException::fromPathname($pathname);
        }

        parent::__construct(\fopen($pathname, 'rb'), true);
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
