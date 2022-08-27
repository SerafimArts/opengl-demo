<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Device\InputDeviceInterface;
use Bic\Lib\BassAL;

/**
 * @template-extends Context<InputDeviceInterface>
 */
final class Input extends Context implements InputInterface
{
    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     * @param InputDeviceInterface $device
     */
    public function __construct(
        BassAL $bass,
        BassAL\Mixer $mixer,
        InputDeviceInterface $device,
    ) {
        parent::__construct($bass, $mixer, $device);

        $this->bass->BASS_RecordInit($device->getId());
    }

    /**
     * {@inheritDoc}
     */
    public function getDevice(): InputDeviceInterface
    {
        return $this->device;
    }
}
