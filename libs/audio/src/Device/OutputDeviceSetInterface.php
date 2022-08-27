<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

/**
 * @template TOutputDevice of OutputDeviceInterface
 *
 * @template-extends DeviceSetInterface<TOutputDevice>
 */
interface OutputDeviceSetInterface extends DeviceSetInterface
{
    /**
     * @return TOutputDevice
     */
    public function getDefault(): OutputDeviceInterface;
}
