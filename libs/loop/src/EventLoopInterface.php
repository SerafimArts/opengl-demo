<?php

declare(strict_types=1);

namespace Bic\Loop;

use React\Promise\PromiseInterface;

interface EventLoopInterface
{
    /**
     * @param \Generator|callable():\Generator $task
     * @return PromiseInterface
     */
    public function attach(\Generator|callable $task): PromiseInterface;

    /**
     * @return void
     */
    public function run(): void;
}
