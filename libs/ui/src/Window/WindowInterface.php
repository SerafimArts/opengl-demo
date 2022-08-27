<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Bic\UI\EventInterface;
use Ramsey\Uuid\UuidInterface;

interface WindowInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return void
     */
    public function close(): void;

    /**
     * @return bool
     */
    public function isClosed(): bool;

    /**
     * @return EventInterface|null
     */
    public function poll(): ?EventInterface;
}
