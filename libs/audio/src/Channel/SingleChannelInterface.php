<?php

declare(strict_types=1);

namespace Bic\Audio\Channel;

use Bic\Audio\Repository\SourceInterface;

/**
 * @psalm-type ChannelID = positive-int
 */
interface SingleChannelInterface extends ChannelInterface
{
    /**
     * @return ChannelID
     */
    public function getId(): int;

    /**
     * @param SourceInterface $source
     * @param bool $repeat
     * @param positive-int $slide
     * @return void
     */
    public function play(SourceInterface $source, bool $repeat = false, int $slide = 1000): void;

    /**
     * @return void
     */
    public function stop(): void;

    /**
     * @return void
     */
    public function pause(): void;

    /**
     * @return void
     */
    public function continue(): void;
}
