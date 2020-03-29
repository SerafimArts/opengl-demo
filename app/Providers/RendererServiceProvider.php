<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application\ServiceProvider;
use Serafim\Bic\Renderer\Driver;
use Serafim\Bic\Renderer\Renderer;
use Serafim\Bic\Renderer\RendererInterface;
use Serafim\Bic\Window\WindowInterface;

/**
 * Class RendererServiceProvider
 */
class RendererServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(RendererInterface::class, function () {
            $window = $this->app->make(WindowInterface::class);

            return $this->createRenderer($window);
        });

        $this->app->alias(RendererInterface::class, Renderer::class);
    }

    /**
     * @param WindowInterface $window
     * @return RendererInterface
     * @throws BindingResolutionException
     */
    private function createRenderer(WindowInterface $window): RendererInterface
    {
        $flags = $this->config('renderer.flags', 0);

        return new Renderer($window, $this->getDriver(), $flags,);
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
