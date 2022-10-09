<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use Serafim\SDL\Kernel\Event\Type;

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
