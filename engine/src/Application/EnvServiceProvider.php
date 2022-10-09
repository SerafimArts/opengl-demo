<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Dotenv\Dotenv;

class EnvServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $env = Dotenv::createImmutable($this->app->path());

        if (\is_file($this->app->path('.env'))) {
            $env->load();
        }

        $this->app->instance(Dotenv::class, $env);
    }
}
