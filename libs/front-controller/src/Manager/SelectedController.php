<?php

declare(strict_types=1);

namespace Bic\Controller\Manager;

use Bic\Dispatcher\DispatcherInterface;

final class SelectedController implements SelectedControllerInterface
{
    /**
     * @param object $controller
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly object $controller,
        private readonly DispatcherInterface $dispatcher,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getController(): object
    {
        return $this->controller;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
