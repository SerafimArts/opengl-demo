<?php

declare(strict_types=1);

namespace Bic\Dispatcher;

use Bic\Dispatcher\Subscriber\Extractor;

final class Subscriber implements SubscriberInterface
{
    /**
     * @var \WeakMap<object, DispatcherInterface>
     */
    private readonly \WeakMap $references;

    public function __construct()
    {
        $this->references = new \WeakMap();
    }

    /**
     * @param object $target
     * @return DispatcherInterface
     */
    public function get(object $target): DispatcherInterface
    {
        return $this->references[$target] ??= $this->create($target);
    }

    /**
     * @param object $target
     * @return DispatcherInterface
     */
    private function create(object $target): DispatcherInterface
    {
        if ($target instanceof DispatcherInterface) {
            return $target;
        }

        return new Extractor($target);
    }
}
