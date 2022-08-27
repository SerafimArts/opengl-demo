<?php

declare(strict_types=1);

namespace Bic\Dispatcher;

interface DispatcherInterface
{
    /**
     * @param object $event
     * @return void
     */
    public function dispatch(object $event): void;
}
