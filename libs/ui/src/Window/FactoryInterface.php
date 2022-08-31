<?php

declare(strict_types=1);

namespace Bic\UI\Window;

/**
 * @template TWindow of WindowInterface
 *
 * @package ui
 */
interface FactoryInterface
{
    /**
     * @param string $name
     * @param positive-int $width
     * @param positive-int $height
     * @return TWindow
     */
    public function create(string $name, int $width, int $height): WindowInterface;
}
