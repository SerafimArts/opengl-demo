<?php

declare(strict_types=1);

namespace Bic\Dispatcher\Subscriber;

use Bic\Dispatcher\Attribute\OnEvent;
use Bic\Dispatcher\DispatcherInterface;

final class Extractor implements DispatcherInterface
{
    /**
     * @template TEvent of object
     * @var array<class-string<TEvent>, array<callable(TEvent):void>>
     */
    private array $events = [];

    /**
     * @param object $target
     */
    public function __construct(object $target)
    {
        $reflection = new \ReflectionObject($target);

        foreach ($reflection->getMethods() as $method) {
            $callback = $method->getClosure($method->isStatic() ? null : $target);

            foreach ($this->getAttributes($method) as $attribute) {
                $this->events[$attribute->class][] = $callback;
            }
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return iterable<OnEvent>
     */
    private function getAttributes(\ReflectionMethod $method): iterable
    {
        foreach ($method->getAttributes(OnEvent::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            yield $attribute->newInstance();
        }
    }

    /**
     * @param object $event
     * @return void
     */
    public function dispatch(object $event): void
    {
        if (!isset($this->events[$event::class])) {
            return;
        }

        foreach ($this->events[$event::class] as $callback) {
            $callback($event);
        }
    }
}
