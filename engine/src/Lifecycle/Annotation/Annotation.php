<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

abstract class Annotation
{
    /**
     * @var string
     */
    protected const DEFAULT_VALUE = 'value';

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($key === 'value') {
                $key = static::DEFAULT_VALUE;
            }

            $this->$key = $value;
        }
    }
}
