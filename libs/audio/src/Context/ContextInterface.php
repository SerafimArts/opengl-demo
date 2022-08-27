<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Device\DeviceInterface;

/**
 * @template TDevice of DeviceInterface
 */
interface ContextInterface
{
    /**
     * @return TDevice
     */
    public function getDevice(): DeviceInterface;
}
