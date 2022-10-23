<?php

declare(strict_types=1);

namespace Bic\UI\SDL\Internal;

use Bic\UI\SDL\Window;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Bic\UI\SDL
 */
final class WindowInstanceInfo
{
    /**
     * @param Window $window
     * @param int $id
     * @param bool $closable
     */
    public function __construct(
        public readonly Window $window,
        public readonly int $id,
        public readonly bool $closable,
    ) {
    }
}