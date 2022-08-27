<?php

declare(strict_types=1);

namespace Bic\Controller;

use Bic\Dispatcher\DispatcherInterface;

interface ManagerInterface extends DispatcherInterface
{
    /**
     * @param object|class-string<object> $controller
     * @return void
     */
    public function use(object|string $controller): void;
}
