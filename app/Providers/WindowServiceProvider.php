<?php

declare(strict_types=1);

namespace App\Providers;

use Bic\UI\FactoryInterface;
use Bic\UI\ManagerInterface;
use Bic\UI\SDL\Factory;
use Bic\UI\SDL\Window;
use Bic\UI\Size;
use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\Mode;
use Bic\UI\Window\WindowInterface;
use Illuminate\Container\Container;
use Serafim\Bic\Application\ServiceProvider;
use Serafim\SDL\SDL;

class WindowServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function () {
            $instance = SDL::getInstance();

            return Factory::fromLibrary($instance->info->bin);
        });

        $this->app->alias(Factory::class, FactoryInterface::class);
        $this->app->alias(Factory::class, ManagerInterface::class);


        $this->app->singleton(Window::class, function (Container $app) {
            /** @var FactoryInterface $windows */
            $windows = $app->make(FactoryInterface::class);

            return $windows->create(new CreateInfo(
                title: $this->config('window.title', 'Bic Engine'),
                size: new Size(
                    width: $this->config('window.width', 800),
                    height: $this->config('window.height', 600),
                ),
                mode: $this->config('window.mode', Mode::DESKTOP_FULLSCREEN)
            ));
        });

        $this->app->alias(Window::class, WindowInterface::class);
    }
}
