<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

interface ChannelInterface
{
    /**
     * @return float
     */
    public function getVolume(): float;

    /**
     * @param float $value
     * @return float Returns real volume
     */
    public function setVolume(float $value): float;
}
