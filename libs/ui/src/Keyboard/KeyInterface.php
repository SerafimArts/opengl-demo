<?php

declare(strict_types=1);

namespace Bic\UI\Keyboard;

/**
 * @psalm-type KeyID = positive-int|0
 * @package ui
 */
interface KeyInterface
{
    /**
     * @return KeyID
     */
    public function getId(): int;
}
