<?php

declare(strict_types=1);

namespace Serafim\Bic;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Serafim\Bic\Application\InteractWithPaths;
use Serafim\Bic\Application\InteractWithProviders;
use Serafim\Bic\EventLoop\LoopInterface;

final class Application extends Container
{
    use InteractWithPaths;
    use InteractWithProviders;

    /**
     * @var string[]
     */
    private const DEFAULT_SERVICE_PROVIDERS = [
        // Kernel
        Application\DirectMediaServiceProvider::class,
        Application\EnvServiceProvider::class,
        Application\ConfigServiceProvider::class,

        // Services
        Application\AnnotationReaderServiceProvider::class,
        Application\EventLoopServiceProvider::class,
    ];

    /**
     * @param string $root
     * @throws BindingResolutionException
     */
    public function __construct(string $root)
    {
        $this->registerSelf();
        $this->registerPaths($root);
        $this->registerDefaultProviders();
    }

    /**
     * @return void
     */
    private function registerSelf(): void
    {
        $this->instance(self::class, $this);
        $this->alias(self::class, ContainerContract::class);
        $this->alias(self::class, Container::class);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    private function registerDefaultProviders(): void
    {
        $this->withProviders(self::DEFAULT_SERVICE_PROVIDERS);

        $config = $this->make(Repository::class);

        $this->withProviders((array)$config->get('app.providers', []));
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $this->bootServiceProviders();

        $loop = $this->make(LoopInterface::class);
        $loop->run();
    }
}
