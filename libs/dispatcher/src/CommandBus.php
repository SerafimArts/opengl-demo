<?php

declare(strict_types=1);

namespace Bic\Dispatcher;

final class CommandBus implements DelegateDispatcherInterface
{
    /**
     * @var \SplObjectStorage<DispatcherInterface>
     */
    private readonly \SplObjectStorage $dispatchers;

    /**
     * @param iterable<DispatcherInterface> $dispatchers
     */
    public function __construct(
        iterable $dispatchers = [],
    ) {
        $this->dispatchers = new \SplObjectStorage();

        foreach ($dispatchers as $dispatcher) {
            $this->attach($dispatcher);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attach(DispatcherInterface $target): void
    {
        $this->dispatchers->attach($target);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(DispatcherInterface $target): void
    {
        $this->dispatchers->detach($target);
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event): void
    {
        foreach ($this->dispatchers as $dispatcher) {
            $dispatcher->dispatch($event);
        }
    }
}
