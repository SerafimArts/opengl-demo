<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Application;

/**
 * Trait InteractWithProviders
 */
trait InteractWithProviders
{
    /**
     * @var array|ServiceProvider[]
     */
    private array $providers = [];

    /**
     * @param string|ServiceProvider $class
     * @return void
     */
    public function withProvider(string $class): void
    {
        /** @var ServiceProvider $provider */
        $provider = new $class($this);

        if (\method_exists($provider, 'boot')) {
            $this->providers[] = $provider;
        }

        $provider->register();
    }

    /**
     * @param iterable|string[]|ServiceProvider[] $providers
     * @return void
     */
    public function withProviders(iterable $providers): void
    {
        foreach ($providers as $provider) {
            $this->withProvider($provider);
        }
    }

    /**
     * @return void
     */
    private function bootServiceProviders(): void
    {
        while (\count($this->providers) > 0) {
            $provider = \array_shift($this->providers);

            $this->call([$provider, 'boot']);
        }
    }
}
