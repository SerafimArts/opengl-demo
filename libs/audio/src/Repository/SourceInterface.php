<?php

declare(strict_types=1);

namespace Bic\Audio\Repository;

/**
 * @psalm-type SourceID = positive-int
 */
interface SourceInterface
{
    /**
     * @return SourceID
     */
    public function getId(): int;
}
