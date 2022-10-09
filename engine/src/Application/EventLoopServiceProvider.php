<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application;
use Serafim\Bic\EventLoop\LoopInterface;
use Serafim\Bic\EventLoop\OrderedEventLoop;

class EventLoopServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LoopInterface::class, function (Application $app): LoopInterface {
            return $this->resolve($app);
        });

        $this->app->alias(LoopInterface::class, 'loop');
    }

    /**
     * @param Application $app
     * @return LoopInterface
     * @throws BindingResolutionException
     */
    private function resolve(Application $app): LoopInterface
    {
        return $app->make(OrderedEventLoop::class);
    }
}
