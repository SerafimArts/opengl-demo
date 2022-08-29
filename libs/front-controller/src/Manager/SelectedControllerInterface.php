<?php

declare(strict_types=1);

namespace Bic\Controller\Manager;

use Bic\Dispatcher\DispatcherInterface;

interface SelectedControllerInterface extends DispatcherInterface
{
    /**
     * @return object
     */
    public function getController(): object;
}
