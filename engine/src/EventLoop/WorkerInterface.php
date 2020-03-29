<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\EventLoop;

use FFI\CData;

/**
 * Interface WorkerInterface
 */
interface WorkerInterface
{
    /**
     * @param float $delta
     * @return void
     */
    public function onUpdate(float $delta): void;

    /**
     * @param float $delta
     * @return void
     */
    public function onRender(float $delta): void;

    /**
     * @param CData $event
     * @return void
     */
    public function onEvent(CData $event): void;

    /**
     * @return void
     */
    public function onPause(): void;

    /**
     * @return void
     */
    public function onResume(): void;
}
