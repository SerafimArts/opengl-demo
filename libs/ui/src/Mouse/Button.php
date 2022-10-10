<?php

declare(strict_types=1);

namespace Bic\UI\Mouse;

enum Button: int implements ButtonInterface
{
    case LEFT = 0;
    case RIGHT = 1;
    case MIDDLE = 2;
    case X1 = 3;
    case X2 = 4;

    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        return $this->value;
    }
}
