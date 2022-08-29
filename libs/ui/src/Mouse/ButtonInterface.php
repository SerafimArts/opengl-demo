<?php

declare(strict_types=1);

namespace Bic\UI\Mouse;

/**
 * @psalm-type ButtonID = positive-int|0
 * @package ui
 */
interface ButtonInterface
{
    /**
     * @return ButtonID
     */
    public function getId(): int;
}
