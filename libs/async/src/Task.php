<?php

declare(strict_types=1);

namespace Bic\Async;

final class Task
{
    /**
     * @template TKey of array-key
     * @template TReturn
     *
     * @param \Fiber<mixed, mixed, TReturn, mixed>|callable():TReturn ...$tasks
     * @return array<TKey, TReturn>
     * @throws \Throwable
     */
    public static function all(\Fiber|callable $task, \Fiber|callable ...$tasks): array
    {
        $result = [];

        // Convert all arguments to linear array of Fibers
        foreach ([$task, ...$tasks] as $i => $task) {
            if (\is_callable($task)) {
                $task = new \Fiber($task);
            }

            $tasks[$i] = $task;
        }

        // Await tasks completion
        while ($tasks !== []) {
            foreach ($tasks as $index => $fiber) {
                if ($fiber->isTerminated()) {
                    unset($tasks[$index]);
                    $result[$index] = $fiber->getReturn();
                    continue;
                }

                switch (false) {
                    case $fiber->isStarted():
                        $step = $fiber->start();
                        \Fiber::getCurrent() && \Fiber::suspend($step);
                        break;

                    case $fiber->isTerminated():
                        $step = $fiber->resume();

                        if (!$fiber->isTerminated() && \Fiber::getCurrent() !== null) {
                            \Fiber::suspend($step);
                        }
                        break;

                    default:
                        unset($tasks[$index]);
                        $result[$index] = $fiber->getReturn();
                }
            }
        }

        \ksort($result);

        return $result;
    }

    /**
     * @template TStart
     * @template TResume
     * @template TReturn
     * @template TSuspend
     *
     * @param \Fiber<TStart, TResume, TReturn, TSuspend> $fiber
     * @param TStart ...$args
     * @return \Generator<array-key, TResume, TReturn, TSuspend>
     * @throws \Throwable
     */
    public static function toCoroutine(\Fiber $fiber, mixed ...$args): \Generator
    {
        $index = -1; // Note: Pre-increment is faster than post-increment.
        $value = null;

        // Allow an already running fiber.
        if (!$fiber->isStarted()) {
            $value = $fiber->start(...$args);

            if (!$fiber->isTerminated()) {
                $value = yield ++$index => $value;
            }
        }

        // A Fiber without suspends should return the result immediately.
        if (!$fiber->isTerminated()) {
            while (true) {
                $value = $fiber->resume($value);

                // The last call to "resume()" moves the execution of the
                // Fiber to the "return" stmt.
                //
                // So the "yield" is not needed. Skip this step and return
                // the result.
                if ($fiber->isTerminated()) {
                    break;
                }

                $value = yield ++$index => $value;
            }
        }

        return $fiber->getReturn();
    }

    public static function async(callable $task, mixed ...$args): \Fiber
    {
        $fiber = new \Fiber($task);
        $fiber->start(...$args);

        return $fiber;
    }
}
