<?php

declare(strict_types=1);

namespace Bic\UI;

use Bic\UI\Window\CreateInfo;
use Bic\UI\Window\WindowInterface;

/**
 * @template TWindow of WindowInterface
 * @template TCreateInfo of CreateInfo
 */
interface FactoryInterface
{
    /**
     * @param TCreateInfo $info
     *
     * @return TWindow
     */
    public function create(CreateInfo $info = new CreateInfo()): WindowInterface;
}
