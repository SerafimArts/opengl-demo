<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Application;

use Dotenv\Dotenv;

/**
 * Class EnvServiceProvider
 */
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
