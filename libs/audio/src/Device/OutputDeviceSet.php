<?php

declare(strict_types=1);

namespace Bic\Audio\Device;

use Bic\Audio\Set;
use Bic\Lib\BassAL;
use FFI\CData;

/**
 * @psalm-import-type DeviceID from DeviceInterface
 *
 * @template-extends Set<OutputDevice>
 * @template-implements OutputDeviceSetInterface<OutputDevice>
 */
final class OutputDeviceSet extends Set implements OutputDeviceSetInterface
{
    /**
     * @var array<DeviceID, OutputDevice>
     */
    private array $identityMap = [];

    /**
     * @param BassAL $bass
     */
    public function __construct(
        private readonly BassAL $bass,
    ) {
    }

    /**
     * @return iterable<int, BassAL\CData\BASS_DEVICEINFO>
     * @psalm-return iterable<DeviceID, CData>
     *
     * @psalm-suppress MixedOperand
     * @psalm-suppress UndefinedPropertyFetch
     */
    private function enumerateAudioDevices(): iterable
    {
        $this->bass->BASS_SetConfig(BassAL::BASS_CONFIG_UNICODE, 1);
        $this->bass->BASS_SetConfig(BassAL::BASS_CONFIG_DEV_DEFAULT, 0);

        /** @var CData $info */
        $info = $this->bass->new('BASS_DEVICEINFO');

        for ($id = 0; $this->bass->BASS_GetDeviceInfo($id, \FFI::addr($info)); ++$id) {
            if ($info->flags & BassAL::BASS_DEVICE_ENABLED) {
                yield $id => $info;
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MixedOperand
     * @psalm-suppress UndefinedPropertyFetch
     */
    public function getDefault(): OutputDevice
    {
        foreach ($this->enumerateAudioDevices() as $id => $info) {
            if ($info->flags & BassAL::BASS_DEVICE_DEFAULT) {
                return $this->create($id, $info);
            }
        }

        throw new \LogicException('Could not find available default output device');
    }

    /**
     * @param DeviceID $id
     * @param BassAL\CData\BASS_DEVICEINFO $info
     * @psalm-param CData $info
     * @return OutputDevice
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress UndefinedPropertyFetch
     */
    private function create(int $id, CData $info): OutputDevice
    {
        return $this->identityMap[$id] ??= new OutputDevice($id, $info->name);
    }

    /**
     * @return iterable<array-key, OutputDevice>
     */
    protected function enumerate(): iterable
    {
        foreach ($this->enumerateAudioDevices() as $id => $info) {
            yield $this->create($id, $info);
        }
    }
}
