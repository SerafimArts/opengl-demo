<?php

declare(strict_types=1);

namespace Bic\Audio;

use Bic\Audio\Context\InputInterface;
use Bic\Audio\Context\SamplingFrequency;
use Bic\Audio\Device\InputDeviceInterface;
use Bic\Audio\Device\InputDeviceSetInterface;
use Bic\Audio\Device\OutputDeviceInterface;
use Bic\Audio\Device\OutputDeviceSetInterface;
use Bic\Audio\Context\OutputInterface;

/**
 * @psalm-import-type SamplingFrequencyValue from SamplingFrequency
 */
interface EngineInterface
{
    /**
     * @return RepositoryInterface
     */
    public function getSources(): RepositoryInterface;

    /**
     * @param InputDeviceInterface|null $device
     * @return InputInterface
     */
    public function getInput(InputDeviceInterface $device = null): InputInterface;

    /**
     * @return InputDeviceSetInterface
     */
    public function getInputDevices(): InputDeviceSetInterface;

    /**
     * @return InputDeviceInterface
     */
    public function getDefaultInputDevice(): InputDeviceInterface;

    /**
     * @param SamplingFrequencyValue $frequency
     * @param OutputDeviceInterface|null $device
     * @return OutputInterface
     */
    public function getOutput(OutputDeviceInterface $device = null): OutputInterface;

    /**
     * @return OutputDeviceSetInterface
     */
    public function getOutputDevices(): OutputDeviceSetInterface;

    /**
     * @return OutputDeviceInterface
     */
    public function getDefaultOutputDevice(): OutputDeviceInterface;
}
