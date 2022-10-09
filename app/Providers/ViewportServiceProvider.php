<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Serafim\Bic\Application\ServiceProvider;
use Serafim\Bic\Math\Vector2;
use Serafim\Bic\Renderer\Viewport\StretchViewport;
use Serafim\Bic\Renderer\Viewport\ViewportInterface;
use Serafim\Bic\Window\WindowInterface;

class ViewportServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ViewportInterface::class, function () {
            $window = $this->app->make(WindowInterface::class);

            $class = $this->config('viewport.type', StretchViewport::class);

            return new $class($window, $this->getSize());
        });
    }

    /**
     * @return Vector2
     * @throws BindingResolutionException
     */
    private function getSize(): Vector2
    {
        return new Vector2(
            (float)$this->config('viewport.width', 1920),
            (float)$this->config('viewport.height', 1080),
        );
    }
}
