<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

/**
 * @template TInputDevice of InputDeviceInterface
 *
 * @template-extends DeviceSetInterface<TInputDevice>
 */
interface InputDeviceSetInterface extends DeviceSetInterface
{
    /**
     * @return TInputDevice
     */
    public function getDefault(): InputDeviceInterface;
}
