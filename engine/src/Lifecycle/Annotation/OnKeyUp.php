<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use Bic\UI\Keyboard\KeyInterface;
use Serafim\SDL\Kernel\Event\Type;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnKeyUp extends OnEvent
{
    public function __construct(
        public readonly KeyInterface $key,
    ) {
        parent::__construct(Type::SDL_KEYUP);
    }
}
