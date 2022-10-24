<?php

declare(strict_types=1);

namespace Bic\Loop;

final class Task
{
    /**
     * @param \Fiber|\Generator|callable ...$tasks
     * @return \Generator
     * @throws \Throwable
     */
    public static function all(\Fiber|\Generator|callable ...$tasks): \Generator
    {
        $result = $coroutines = [];

        foreach ($tasks as $task) {
            $coroutines[] = self::toCoroutine($task);
        }

        while ($coroutines !== []) {
            /** @var \Generator $coroutine */
            foreach ($coroutines as $index => $coroutine) {
                if ($coroutine->valid()) {
                    $coroutine->send(yield $coroutine->key() => $coroutine->current());
                } else {
                    $result[$index] = $coroutine->getReturn();
                }
            }
        }

        return $result;
    }

    private static function toCoroutine(\Fiber|\Generator|callable $task, mixed ...$args): \Generator
    {
        return match (true) {
            $task instanceof \Generator => $task,
            $task instanceof \Fiber => self::fiberToCoroutine($task),
            default => self::callableToCoroutine($task),
        };
    }

    /**
     * @template TStart
     * @template TReturn
     *
     * @param callable(TStart):TReturn $fn
     * @param TStart ...$args
     * @return \Generator<array-key, mixed, TReturn, mixed>
     * @throws \Throwable
     */
    public static function callableToCoroutine(callable $fn, mixed ...$args): \Generator
    {
        return self::fiberToCoroutine(new \Fiber($fn), ...$args);
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
    public static function fiberToCoroutine(\Fiber $fiber, mixed ...$args): \Generator
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

    public static function coroutineToFiber(\Generator $task): \Fiber
    {
        return new \Fiber(function () use ($task) {
            while ($task->valid()) {
                $task->send(\Fiber::suspend($task->current()));
            }

            return $task->getReturn();
        });
    }

    public static function async(callable $task, mixed ...$args): \Fiber
    {
        $fiber = new \Fiber($task);
        $fiber->start(...$args);

        return $fiber;
    }

    /**
     * @template TStart
     * @template TReturn
     *
     * @param \Fiber<TStart,mixed,TReturn,mixed>|\Generator<array-key,mixed,TReturn,mixed>|callable(TStart):TReturn $task
     * @param TStart ...$args
     * @return TReturn
     * @throws \Throwable
     */
    public static function await(\Fiber|\Generator|callable $task, mixed ...$args): mixed
    {
        $coroutine = self::toCoroutine($task, ...$args);

        if (\Fiber::getCurrent()) {
            while ($coroutine->valid()) {
                $coroutine->send(\Fiber::suspend($coroutine->current()));
            }
        } else {
            foreach ($coroutine as $_);
        }

        return $coroutine->getReturn();
    }
}
