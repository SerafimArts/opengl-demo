<?php

declare(strict_types=1);

namespace Serafim\Bic\Lifecycle\Annotation;

use Serafim\SDL\Kernel\Event\Type;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnMouseWheel extends OnEvent
{
    public function __construct()
    {
        parent::__construct(Type::SDL_MOUSEWHEEL);
    }
}
