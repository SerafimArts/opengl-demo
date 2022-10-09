<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use Serafim\SDL\Kernel\Event\Type;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnKeyDown extends OnEvent
{
    public function __construct(
        public readonly int $code,
    ) {
        parent::__construct(Type::SDL_KEYDOWN);
    }
}
