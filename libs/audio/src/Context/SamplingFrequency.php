<?php

declare(strict_types=1);

namespace Bic\Audio\Context;

/**
 * @psalm-type SamplingFrequencyCase = SamplingFrequency::FREQ_*
 * @psalm-type SamplingFrequencyValue = positive-int | SamplingFrequencyCase
 */
interface SamplingFrequency
{
    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_LOWEST = 8000;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_LOW = 16000;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_MEDIUM = 32000;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_DECENT = 44100;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_GOOD = 48000;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_EXCELLENT = 96000;

    /**
     * @var SamplingFrequencyCase
     */
    public const FREQ_BEST = 192000;
}
