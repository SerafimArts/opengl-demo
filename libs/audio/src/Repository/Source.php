<?php

declare(strict_types=1);

namespace Bic\Audio\Repository;

/**
 * @psalm-import-type SourceID from SourceInterface
 */
class Source implements SourceInterface
{
    /**
     * @param SourceID $id
     */
    public function __construct(
        protected readonly int $id,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        return $this->id;
    }
}
