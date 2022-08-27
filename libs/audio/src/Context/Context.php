<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Device\DeviceInterface;
use Bic\Lib\BassAL;

/**
 * @template TDevice of DeviceInterface
 *
 * @template-implements ContextInterface<TDevice>
 */
abstract class Context implements ContextInterface
{
    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     * @param TDevice $device
     */
    public function __construct(
        protected readonly BassAL $bass,
        protected readonly BassAL\Mixer $mixer,
        protected readonly DeviceInterface $device,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }
}
