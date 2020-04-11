<?php

/**
 * This file is part of UnknownPlatformer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\EventLoop;

/**
 * Interface LoopInterface
 */
interface LoopInterface
{
    /**
     * @var int
     */
    public const DEFAULT_FRAME_RATE = 60;

    /**
     * @var int
     */
    public const DEFAULT_UPDATE_RATE = 60;

    /**
     * @param int $frameRate
     * @param int $updateRate
     * @return void
     */
    public function run(int $frameRate = self::DEFAULT_FRAME_RATE, int $updateRate = self::DEFAULT_UPDATE_RATE): void;

    /**
     * @param WorkerInterface|null $worker
     * @return void
     */
    public function use(?WorkerInterface $worker): void;

    /**
     * @return void
     */
    public function pause(): void;

    /**
     * @return void
     */
    public function resume(): void;

    /**
     * @return void
     */
    public function stop(): void;
}
