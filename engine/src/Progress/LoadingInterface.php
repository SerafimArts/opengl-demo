<?php

declare(strict_types=1);

namespace Serafim\Bic\Progress;

interface LoadingInterface
{
    /**
     * @return string|null
     */
    public function next(): ?string;

    /**
     * @return bool
     */
    public function isCompleted(): bool;

    /**
     * @return mixed
     */
    public function result();
}
