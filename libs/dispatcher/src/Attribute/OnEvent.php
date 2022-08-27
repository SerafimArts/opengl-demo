<?php

declare(strict_types=1);

namespace Bic\Dispatcher\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class OnEvent
{
    /**
     * @param class-string $class
     */
    public function __construct(
        public readonly string $class,
    ) {
    }
}
