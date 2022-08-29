<?php

declare(strict_types=1);

namespace Bic\Controller;

use Bic\Dispatcher\DispatcherInterface;

/**
 * @template TController of object
 */
interface ManagerInterface extends DispatcherInterface
{
    /**
     * @param TController|class-string<TController> $controller
     * @return void
     */
    public function use(object|string $controller): void;
}
