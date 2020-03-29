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
use Serafim\Bic\Window\Window;
use Serafim\Bic\Window\WindowInterface;

/**
 * Class WindowServiceProvider
 */
class WindowServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(WindowInterface::class, function () {
            $window = $this->createWindow();

            if ($icon = $this->config('window.icon')) {
                $window->setIconFromPathname(\realpath($icon) ?: $icon);
            }

            return $window;
        });

        $this->app->alias(WindowInterface::class, Window::class);
    }

    /***
     * @return WindowInterface|Window
     * @throws BindingResolutionException
     */
    private function createWindow(): WindowInterface
    {
        return Window::create(
            $this->config('window.title', 'Bic Engine'),
            $this->config('window.width', 800),
            $this->config('window.height', 600),
            $this->config('window.flags', 0),
        );
    }
}
