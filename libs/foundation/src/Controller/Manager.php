<?php

declare(strict_types=1);

namespace Bic\Foundation\Controller;

use Bic\Dispatcher\DispatcherInterface;
use Bic\Dispatcher\SubscriberInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Manager implements ManagerInterface
{
    /**
     * @var object|null
     */
    private ?object $controller = null;

    /**
     * @var DispatcherInterface|null
     */
    private ?DispatcherInterface $dispatcher = null;

    /**
     * @param ContainerInterface $container
     * @param SubscriberInterface $subscriber
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly SubscriberInterface $subscriber,
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
     * @param object|string $controller
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function use(object|string $controller): void
    {
        $this->controller = $this->instance($controller);
        $this->dispatcher = $this->subscriber->get($this->controller);
    }

    /**
     * @param object $event
     * @return void
     */
    public function dispatch(object $event): void
    {
        $this->dispatcher?->dispatch($event);
    }
}
