<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

interface ChannelInterface
{
    /**
     * @psalm-return float
     * @return float<0.0, 1.0>
     */
    public function getVolume(): float;

    /**
     * @psalm-param float $value
     * @param float<0.0, 1.0> $value
     * @return float<0.0, 1.0> Returns real volume
     */
    public function setVolume(float $value): float;
}
