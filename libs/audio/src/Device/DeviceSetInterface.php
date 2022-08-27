<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

use Bic\Audio\SetInterface;

/**
 * @template TDevice of DeviceInterface
 *
 * @template-extends SetInterface<TDevice>
 */
interface DeviceSetInterface extends SetInterface
{
    /**
     * @return TDevice
     */
    public function getDefault(): DeviceInterface;
}
