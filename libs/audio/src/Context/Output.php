<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Channel\MusicChannel;
use Bic\Audio\Channel\SingleChannelInterface;
use Bic\Audio\Device\OutputDeviceInterface;
use Bic\Audio\Exception\AudioException;
use Bic\Lib\BassAL;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @template-extends Context<OutputDeviceInterface>
 *
 * @psalm-import-type SamplingFrequencyValue from SamplingFrequency
 */
final class Output extends Context implements OutputInterface
{
    /**
     * @var MusicChannel
     */
    public readonly MusicChannel $music;

    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     * @param OutputDeviceInterface $device
     * @param SamplingFrequencyValue $frequency
     */
    public function __construct(
        BassAL $bass,
        BassAL\Mixer $mixer,
        OutputDeviceInterface $device,
        #[ExpectedValues(valuesFromClass: SamplingFrequency::class)]
        private int $frequency = SamplingFrequency::FREQ_DECENT,
    ) {
        parent::__construct($bass, $mixer, $device);

        $this->bass->BASS_Init($this->device->getId(), $this->frequency, BassAL::BASS_DEVICE_STEREO, null, null);

        $this->music = new MusicChannel($bass, $mixer, $this->frequency);
    }

    /**
     * {@inheritDoc}
     */
    #[ExpectedValues(valuesFromClass: SamplingFrequency::class)]
    public function getFrequency(): int
    {
        return $this->frequency;
    }

    /**
     * @param SamplingFrequencyValue $frequency
     * @return void
     * @throws AudioException
     */
    public function setFrequency(
        #[ExpectedValues(valuesFromClass: SamplingFrequency::class)]
        int $frequency
    ): void {
        if ($this->frequency === $frequency) {
            return;
        }

        $flags = BassAL::BASS_DEVICE_STEREO | BassAL::BASS_DEVICE_REINIT | BassAL::BASS_DEVICE_FREQ;
        $this->bass->BASS_Init($this->device->getId(), $frequency, $flags, null, null);

        /** @var int $error */
        $error = $this->bass->BASS_ErrorGetCode();

        if ($error === BassAL::BASS_OK) {
            $this->frequency = $frequency;
            return;
        }

        throw AudioException::fromErrorCode($error);
    }

    /**
     * {@inheritDoc}
     */
    public function getMusicChannel(): SingleChannelInterface
    {
        return $this->music;
    }

    /**
     * {@inheritDoc}
     */
    public function getDevice(): OutputDeviceInterface
    {
        return $this->device;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->bass->BASS_SetDevice($this->device->getId());
        $this->bass->BASS_Free();
    }
}
