<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

use Bic\Lib\BassAL;

abstract class Channel implements ChannelInterface
{
    /**
     * @var int
     */
    public const SAMPLE_FLAGS = BassAL::BASS_SAMPLE_FLOAT;

    /**
     * @var float
     */
    protected float $volume = 1.0;

    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     */
    public function __construct(
        protected readonly BassAL $bass,
        protected readonly BassAL\Mixer $mixer,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getVolume(): float
    {
        return $this->volume;
    }

    /**
     * {@inheritDoc}
     */
    public function setVolume(float $value): float
    {
        return $this->volume = \max(0.0, \min(1.0, $value));
    }
}
