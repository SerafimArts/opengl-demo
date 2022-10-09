<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnEvent extends LifecycleAttribute
{
    public function __construct(
        public readonly int $type,
    ) {
    }
}
