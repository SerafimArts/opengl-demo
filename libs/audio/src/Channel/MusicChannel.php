<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

use Bic\Audio\Context\SamplingFrequency;
use Bic\Audio\Repository\SourceInterface;
use Bic\Lib\BassAL;

/**
 * @psalm-import-type SamplingFrequencyValue from SamplingFrequency
 * @psalm-import-type ChannelID from SingleChannelInterface
 */
class MusicChannel extends Channel implements SingleChannelInterface
{
    /**
     * @var ChannelID
     */
    private readonly int $id;

    /**
     * @var SourceInterface|null
     */
    private ?SourceInterface $current = null;

    /**
     * @param BassAL $bass
     * @param BassAL\Mixer $mixer
     * @param SamplingFrequencyValue $frequency
     */
    public function __construct(BassAL $bass, BassAL\Mixer $mixer, int $frequency)
    {
        parent::__construct($bass, $mixer);

        $this->id = $this->mixer->BASS_Mixer_StreamCreate(
            $frequency,
            Channels::STEREO->value,
            self::SAMPLE_FLAGS,
        );
    }

    /**
     * @return ChannelID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function setVolume(float $value): float
    {
        $value = parent::setVolume($value);

        $this->bass->BASS_ChannelSetAttribute($this->id, BassAL::BASS_ATTRIB_VOL, $value);

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function play(SourceInterface $source, bool $repeat = false, int $slide = 1000): void
    {
        if ($this->current !== null) {
            $this->bass->BASS_ChannelStop($this->id);
            $this->mixer->BASS_Mixer_ChannelRemove($this->current->getId());
        }

        if ($slide > 0) {
            $this->bass->BASS_ChannelSetAttribute($this->id, BassAL::BASS_ATTRIB_VOL, 0);
            $this->bass->BASS_ChannelSlideAttribute($this->id, BassAL::BASS_ATTRIB_VOL, $this->volume, $slide);
        }

        $this->mixer->BASS_Mixer_StreamAddChannel($this->id, $source->getId(), 0);

        if ($repeat) {
            $this->bass->BASS_ChannelFlags($source->getId(), BassAL::BASS_SAMPLE_LOOP, BassAL::BASS_SAMPLE_LOOP);
        } else {
            $this->bass->BASS_ChannelFlags($source->getId(), 0, BassAL::BASS_SAMPLE_LOOP);
        }

        $this->bass->BASS_ChannelPlay($this->id, 0);

        $this->current = $source;
    }

    /**
     * {@inheritDoc}
     */
    public function stop(): void
    {
        if ($this->bass->BASS_ChannelIsActive($this->id)) {
            $this->bass->BASS_ChannelStop($this->id);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function pause(): void
    {
        if ($this->bass->BASS_ChannelIsActive($this->id)) {
            $this->bass->BASS_ChannelPause($this->id);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function continue(): void
    {
        if ($this->current !== null) {
            $this->bass->BASS_ChannelPlay($this->id, 0);
        }
    }

    public function __destruct()
    {
        $this->stop();
        $this->bass->BASS_ChannelFree($this->id);
    }
}
