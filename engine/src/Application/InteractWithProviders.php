<?php

declare(strict_types=1);

namespace Serafim\Bic\Application;
"*"
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
