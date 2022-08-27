<?php

declare(strict_types=1);

namespace Bic\Audio;

use Bic\Audio\Context\Input;
use Bic\Audio\Context\Output;
use Bic\Audio\Device\InputDevice;
use Bic\Audio\Device\InputDeviceInterface;
use Bic\Audio\Device\InputDeviceSet;
use Bic\Audio\Device\OutputDevice;
use Bic\Audio\Device\OutputDeviceInterface;
use Bic\Audio\Device\OutputDeviceSet;
use Bic\Lib\BassAL;

final class Engine implements EngineInterface
{
    /**
     * @var OutputDeviceSet
     */
    private readonly OutputDeviceSet $outputDevices;

    /**
     * @var \WeakMap<OutputDevice, Output>
     */
    private readonly \WeakMap $outputs;

    /**
     * @var InputDeviceSet
     */
    private readonly InputDeviceSet $inputDevices;

    /**
     * @var \WeakMap<InputDevice, Input>
     */
    private readonly \WeakMap $inputs;

    /**
     * @var RepositoryInterface
     */
    public readonly RepositoryInterface $sources;

    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     */
    public function __construct(
        private readonly BassAL $bass,
        private readonly BassAL\Mixer $mixer,
    ) {
        $this->outputs = new \WeakMap();
        $this->inputs = new \WeakMap();

        $this->outputDevices = new OutputDeviceSet($this->bass);
        $this->inputDevices = new InputDeviceSet($this->bass);
        $this->sources = new Repository($this->bass);
    }

    /**
     * {@inheritDoc}
     */
    public function getSources(): RepositoryInterface
    {
        return $this->sources;
    }

    /**
     * {@inheritDoc}
     */
    public function getInput(InputDeviceInterface $device = null): Input
    {
        $device ??= $this->getDefaultInputDevice();

        return $this->inputs[$device] ??= new Input($this->bass, $this->mixer, $device);
    }

    /**
     * {@inheritDoc}
     */
    public function getInputDevices(): InputDeviceSet
    {
        return $this->inputDevices;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultInputDevice(): InputDeviceInterface
    {
        return $this->inputDevices->getDefault();
    }

    /**
     * @param OutputDeviceInterface|null $device
     * @return Output
     */
    public function getOutput(OutputDeviceInterface $device = null): Output
    {
        $device ??= $this->getDefaultOutputDevice();

        return $this->outputs[$device] ??= new Output($this->bass, $this->mixer, $device);
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputDevices(): OutputDeviceSet
    {
        return $this->outputDevices;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultOutputDevice(): OutputDeviceInterface
    {
        return $this->outputDevices->getDefault();
    }
}
