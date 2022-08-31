<?php

declare(strict_types=1);

namespace Bic\Tiled\Map;

enum RenderOrder: string
{
    case RIGHT_DOWN = 'right-down';
    case RIGHT_UP = 'right-up';
    case LEFT_DOWN = 'left-down';
    case LEFT_UP = 'left-up';
}
