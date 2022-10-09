<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

/**
 * @Annotation
 */
class OnEvent extends Lifecycle
{
    /**
     * @var string
     */
    protected const DEFAULT_VALUE = 'type';

    /**
     * @var int
     */
    public ?int $type = null;
}
