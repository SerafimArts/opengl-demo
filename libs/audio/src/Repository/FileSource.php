<?php

declare(strict_types=1);

namespace Bic\Audio\Repository;

/**
 * @psalm-import-type SourceID from SourceInterface
 */
class FileSource extends Source implements FileSourceInterface
{
    /**
     * @psalm-taint-sink file $pathname
     * @param SourceID $id
     * @param non-empty-string $pathname
     */
    public function __construct(
        int $id,
        protected readonly string $pathname,
    ) {
        parent::__construct($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getPathname(): string
    {
        return $this->pathname;
    }
}
