<?php

declare(strict_types=1);

namespace Bic\Dispatcher;

interface SubscriberInterface
{
    /**
     * @param object $target
     * @return DispatcherInterface
     */
    public function get(object $target): DispatcherInterface;
}
