<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application\ServiceProvider;
use Serafim\Bic\Renderer\Driver;
use Serafim\Bic\Renderer\Renderer;
use Serafim\Bic\Renderer\RendererInterface;
use Bic\UI\SDL\Window;

class RendererServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(RendererInterface::class, function () {
            $window = $this->app->make(Window::class);

            return $this->createRenderer($window);
        });

        $this->app->alias(RendererInterface::class, Renderer::class);
    }

    /**
     * @param Window $window
     * @return RendererInterface
     * @throws BindingResolutionException
     */
    private function createRenderer(Window $window): RendererInterface
    {
        $flags = $this->config('renderer.flags', 0);

        return new Renderer($window, $this->getDriver(), $flags);
    }

    /**
     * @return Driver|null
     * @throws BindingResolutionException
     */
    private function getDriver(): Driver
    {
        $driver = $this->config('renderer.driver');

        switch (true) {
            case \is_int($driver):
                return Driver::findById($driver);

            case \is_string($driver):
                return Driver::findByName($driver);

            case $driver instanceof Driver:
                return $driver;

            default:
                return Driver::current();
        }
    }
}
