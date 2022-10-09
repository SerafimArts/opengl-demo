<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Serafim\Bic\Application;

/**
 * @property-read Application $app
 */
abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function config(string $key, $default = null)
    {
        $config = $this->app->make(Repository::class);

        return $config->get($key, $default);
    }
}
