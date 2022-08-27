<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

use Bic\Audio\Channel\SingleChannelInterface;
use Bic\Audio\Device\OutputDeviceInterface;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @psalm-import-type SamplingFrequencyValue from SamplingFrequency
 *
 * @template-extends ContextInterface<OutputDeviceInterface>
 */
interface OutputInterface extends ContextInterface
{
    /**
     * @return OutputDeviceInterface
     */
    public function getDevice(): OutputDeviceInterface;

    /**
     * @return SamplingFrequencyValue
     */
    #[ExpectedValues(valuesFromClass: SamplingFrequency::class)]
    public function getFrequency(): int;

    /**
     * @param SamplingFrequencyValue $frequency
     * @return void
     */
    public function setFrequency(
        #[ExpectedValues(valuesFromClass: SamplingFrequency::class)]
        int $frequency,
    ): void;

    /**
     * @return SingleChannelInterface
     */
    public function getMusicChannel(): SingleChannelInterface;
}
