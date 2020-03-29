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
 * Trait InteractWithPaths
 */
trait InteractWithPaths
{
    /**
     * @param string $path
     * @return string
     */
    private function formatPath(string $path): string
    {
        return \rtrim(\str_replace('\\', '/', $path), '/');
    }

    /**
     * @param string $path
     * @return string
     */
    public function path(string $path = ''): string
    {
        return $this->formatPath($this['path'] . '/' . $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function resourcesPath(string $path = ''): string
    {
        return $this->formatPath($this['path.resources'] . '/' . $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function configPath(string $path = ''): string
    {
        return $this->formatPath($this['path.config'] . '/' . $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function storagePath(string $path = ''): string
    {
        return $this->formatPath($this['path.storage'] . '/' . $path);
    }

    /**
     * @param string $path
     * @return void
     */
    private function registerPaths(string $path): void
    {
        $path = $this->formatPath($path);

        $this->instance('path', $path);
        $this->instance('path.resources', $path . '/resources');
        $this->instance('path.config', $path . '/config');
        $this->instance('path.storage', $path . '/storage');
    }
}
