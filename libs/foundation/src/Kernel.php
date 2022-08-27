<?php

/**
 * This file is part of Bic Engine package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bic\Foundation;

use Bic\Dispatcher\CommandBus;
use Bic\Dispatcher\DelegateDispatcherInterface;
use Bic\Dispatcher\DispatcherInterface;
use Bic\Foundation\DependencyInjection\CompilerPass\EventDispatcherCompilerPass;
use Bic\Foundation\Event\AppComplete;
use Bic\Foundation\Event\AppStarted;
use Bic\Foundation\Exception\Factory;
use Bic\Foundation\Exception\HandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class Kernel implements KernelInterface, DispatcherInterface
{
    /**
     * @var Container
     */
    private readonly Container $container;

    /**
     * @var DelegateDispatcherInterface
     */
    private readonly DelegateDispatcherInterface $dispatcher;

    /**
     * @psalm-taint-sink file $root
     *
     * @param bool $debug
     * @param non-empty-string $root
     * @param HandlerInterface $exception
     * @throws \Exception
     */
    public function __construct(
        private readonly string $root,
        protected readonly bool $debug = false,
        protected readonly HandlerInterface $exception = new Factory(),
    ) {
        $this->listenErrors();

        try {
            $this->container = $this->getCachedContainer(
                $this->getContainerPathname(),
                $this->getContainerClass(),
            );

            $this->extendContainerDefinitions($this->container);

            $this->dispatcher = $this->container->get(DelegateDispatcherInterface::class);
        } catch (\Throwable $e) {
            exit($this->throw($e));
        }
    }

    /**
     * @param int $level
     * @return void
     * @throws \Exception
     */
    private function listenErrors(int $level = \E_ALL): void
    {
        \set_error_handler(function (int $code, string $message, string $file, int $line): void {
            $this->throw(new \ErrorException($message, $code, $code, $file, $line));
        }, $level);
    }

    /**
     * @param \Throwable $e
     * @return int
     * @throws \Exception
     */
    public function throw(\Throwable $e): int
    {
        $this->log($e);

        return $this->exception->throw($e);
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }

    /**
     * @param \Throwable $e
     * @return void
     * @throws \Exception
     */
    private function log(\Throwable $e): void
    {
        try {
            if (!$this->container->has(LoggerInterface::class)) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        $logger = $this->container->get(LoggerInterface::class);
        $logger->log(match ($e instanceof \ErrorException ? $e->getSeverity() : \E_ERROR) {
            \E_PARSE => LogLevel::EMERGENCY,
            \E_CORE_ERROR, \E_COMPILE_ERROR, \E_ERROR => LogLevel::ALERT,
            \E_RECOVERABLE_ERROR, \E_USER_ERROR => LogLevel::ERROR,
            \E_WARNING, \E_CORE_WARNING, \E_COMPILE_WARNING, \E_USER_WARNING => LogLevel::WARNING,
            \E_NOTICE, \E_USER_NOTICE, \E_STRICT, \E_DEPRECATED, \E_USER_DEPRECATED => LogLevel::NOTICE,
            default => LogLevel::DEBUG,
        }, $e->getMessage(), ['exception' => $e]);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * @template TEntryObject of object
     *
     * @param class-string $id
     * @return TEntryObject
     *
     * @throws \Exception
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function get(string $id): object
    {
        return $this->container->get($id);
    }

    /**
     * @param non-empty-string $pathname
     * @param class-string<Container> $class
     * @return Container
     * @throws FileLoaderImportCircularReferenceException
     * @throws LoaderLoadException
     */
    private function getCachedContainer(string $pathname, string $class): Container
    {
        if ($this->debug || !\is_file($pathname)) {
            $dumper = new PhpDumper($this->createContainer());

            if (!\is_dir(\dirname($pathname))) {
                \mkdir(\dirname($pathname), recursive: true);
            }

            \file_put_contents($pathname, $dumper->dump(['class' => $class]));
        }

        require $pathname;

        return new $class();
    }

    /**
     * @return ContainerBuilder
     * @throws FileLoaderImportCircularReferenceException
     * @throws LoaderLoadException
     */
    private function createContainer(): ContainerBuilder
    {
        $builder = new ContainerBuilder();

        $this->extendContainerBuilderParameters($builder);
        $this->extendContainerBuilderDefinitions($builder);
        $this->extendContainerBuilderConfigs($builder);
        $this->extendContainerBuilderCompilerPass($builder);

        $builder->compile();

        return $builder;
    }

    /**
     * @param ContainerBuilder $builder
     * @return void
     */
    private function extendContainerBuilderParameters(ContainerBuilder $builder): void
    {
        $builder->setParameter('dir.root', $this->root);
        $builder->setParameter('debug', $this->debug);
        $builder->setParameter('environment', $this->getEnvironment());
        $builder->setParameter('date', \date('Y-m-d'));
    }

    /**
     * @return string
     */
    private function getEnvironment(): string
    {
        return \strtolower(\PHP_OS_FAMILY);
    }

    /**
     * @param ContainerBuilder $builder
     * @return void
     */
    private function extendContainerBuilderDefinitions(ContainerBuilder $builder): void
    {
        $builder->setDefinition(self::class, (new Definition(self::class))->setSynthetic(true));
        $builder->setDefinition(static::class, new ChildDefinition(self::class));
    }

    /**
     * @param ContainerBuilder $builder
     * @return void
     * @throws FileLoaderImportCircularReferenceException
     * @throws LoaderLoadException
     */
    private function extendContainerBuilderConfigs(ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(
            $this->getConfigDirectories(),
        ), $this->getEnvironment());

        $loader->import(__DIR__ . '/../resources/*.yaml');

        foreach ($this->getConfigDirectories() as $directory) {
            $loader->import($directory . '/*.yaml');
            $loader->import($directory . '/*/*.yaml');
        }
    }

    /**
     * @return array<non-empty-string>
     */
    protected function getConfigDirectories(): array
    {
        return [$this->root . '/config'];
    }

    /**
     * @param ContainerBuilder $builder
     * @return void
     */
    private function extendContainerBuilderCompilerPass(ContainerBuilder $builder): void
    {
        $builder->addCompilerPass(new EventDispatcherCompilerPass());
    }

    /**
     * @return non-empty-string
     */
    private function getContainerPathname(): string
    {
        return $this->root . '/storage/' . $this->getContainerClass() . '.php';
    }

    /**
     * @return class-string<Container>
     */
    private function getContainerClass(): string
    {
        return \ucfirst($this->getEnvironment()) . 'AppContainer';
    }

    /**
     * @param Container $container
     * @return void
     */
    private function extendContainerDefinitions(Container $container): void
    {
        $container->set(self::class, $this);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(): int
    {
        try {
            $this->dispatch(new AppStarted($this));
            $this->start();
            $this->dispatch(new AppComplete($this));
        } catch (\Throwable $e) {
            return $this->throw($e);
        }

        return 0;
    }

    /**
     * @return void
     */
    abstract protected function start(): void;
}
