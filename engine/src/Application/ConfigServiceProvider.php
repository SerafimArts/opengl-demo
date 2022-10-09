<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(RepositoryContract::class, function () {
            return new Repository($this->readConfigs());
        });

        $this->app->alias(RepositoryContract::class, Repository::class);
    }

    /**
     * @return array
     */
    private function readConfigs(): array
    {
        $configs = [];

        foreach (\glob($this->app->configPath('*.php')) as $file) {
            $name = \basename($file, '.php');

            $configs[$name] = require $file;
        }

        return $configs;
    }
}
