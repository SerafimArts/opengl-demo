<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use SDL\Kernel\Event\Type;

/**
 * @Annotation
 */
class OnKeyUp extends OnEvent
{
    /**
     * @var string
     */
    protected const DEFAULT_VALUE = 'code';

    /**
     * @var int|null
     */
    public ?int $code = null;

    /**
     * @var int
     */
    public ?int $type = Type::SDL_KEYUP;
}
