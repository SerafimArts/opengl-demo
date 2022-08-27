<?php

declare(strict_types=1);

namespace Bic\Dispatcher;

interface DelegateDispatcherInterface extends DispatcherInterface
{
    /**
     * @param DispatcherInterface $target
     * @return void
     */
    public function attach(DispatcherInterface $target): void;

    /**
     * @param DispatcherInterface $target
     * @return void
     */
    public function detach(DispatcherInterface $target): void;
}
