<?php

declare(strict_types=1);

namespace Bic\Controller;

use Bic\Controller\Event\ControllerHideEvent;
use Bic\Controller\Event\ControllerShowEvent;
use Bic\Controller\Event\ControllerSwitchEvent;
use Bic\Controller\Manager\SelectedController;
use Bic\Controller\Manager\SelectedControllerInterface;
use Bic\Dispatcher\DispatcherInterface;
use Bic\Dispatcher\SubscriberInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Manager implements ManagerInterface
{
    /**
     * @var SelectedControllerInterface|null
     */
    private ?SelectedControllerInterface $current = null;

    /**
     * @param ContainerInterface $container
     * @param SubscriberInterface $subscriber
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly SubscriberInterface $subscriber,
        private readonly DispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @template TController of object
     * @param TController|string $controller
     * @return TController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function instance(object|string $controller): object
    {
        if (\is_object($controller)) {
            return $controller;
        }

        /** @var TController */
        return $this->container->get($controller);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Throwable
     */
    public function use(object|string $controller): void
    {
        if ($this->current !== null) {
            $this->dispatcher->dispatch(new ControllerHideEvent($this->current));
        }

        $instance = $this->instance($controller);
        $dispatcher = $this->subscriber->get($instance);

        $current = new SelectedController($instance, $dispatcher);

        if ($this->current !== null) {
            $this->dispatcher->dispatch(new ControllerSwitchEvent($this->current, $current));
        }

        $this->current = $current;
        $this->dispatcher->dispatch(new ControllerShowEvent($current));
    }

    /**
     * @param object $event
     * @return void
     */
    public function dispatch(object $event): void
    {
        $this->current?->dispatch($event);
    }
}
