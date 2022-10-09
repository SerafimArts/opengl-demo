<?php

declare(strict_types=1);

namespace Serafim\Bic\Renderer\Viewport;

use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Renderer\TransformationInterface;

/**
 * @property-read Vector2 $target
 * @property-read Vector2 $source
 */
interface ViewportInterface extends TransformationInterface
{
}
