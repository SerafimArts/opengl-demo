<?php

declare(strict_types=1);

namespace Bic\Foundation\DependencyInjection\CompilerPass;

use Bic\Dispatcher\CommandBus;
use Bic\Dispatcher\DelegateDispatcherInterface;
use Bic\Dispatcher\DispatcherInterface;
use Bic\Dispatcher\Subscriber;
use Bic\Dispatcher\SubscriberInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class EventDispatcherCompilerPass implements CompilerPassInterface
{
    /**
     * @var non-empty-string
     */
    public const SERVICE_TAG = 'kernel.dispatcher';

    /**
     * @var non-empty-string
     */
    public const DISPATCHER_REFERENCE = 'kernel.dispatcher(%s)';

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $this->registerServices($container);

        $delegate = $container->getDefinition(CommandBus::class);

        foreach ($container->findTaggedServiceIds(self::SERVICE_TAG) as $id => $tags) {
            $dispatcherId = \sprintf(self::DISPATCHER_REFERENCE, $id);

            $container->setDefinition($dispatcherId, $this->createDispatcherDefinition($id));

            $delegate->addMethodCall('attach', [new Reference($dispatcherId)]);
        }
    }

    /**
     * @param ContainerBuilder $builder
     * @return void
     */
    private function registerServices(ContainerBuilder $builder): void
    {
        $builder->setDefinition(Subscriber::class, new Definition(Subscriber::class));
        $builder->setAlias(SubscriberInterface::class, Subscriber::class)
            ->setPublic(true);

        $builder->setDefinition(CommandBus::class, new Definition(CommandBus::class));
        $builder->setAlias(DispatcherInterface::class, CommandBus::class)
            ->setPublic(true);
        $builder->setAlias(DelegateDispatcherInterface::class,CommandBus::class)
            ->setPublic(true);
    }

    /**
     * @param non-empty-string $id
     * @return Definition
     */
    private function createDispatcherDefinition(string $id): Definition
    {
        return (new Definition(DispatcherInterface::class))
            ->setFactory([new Reference(SubscriberInterface::class), 'get'])
            ->setArguments([new Reference($id)]);
    }
}
