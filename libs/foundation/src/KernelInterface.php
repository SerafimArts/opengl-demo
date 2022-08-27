<?php

declare(strict_types=1);

namespace Bic\Foundation;

use Bic\Foundation\Exception\HandlerInterface;
use Psr\Container\ContainerInterface;

interface KernelInterface extends HandlerInterface, ContainerInterface
{
    /**
     * @return int
     */
    public function run(): int;
}
