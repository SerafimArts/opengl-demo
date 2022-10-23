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
        $fibers = self::toFibers($tasks);
        $result = [];

        while ($fibers !== []) {
            /** @var \Fiber $task */
            foreach ($fibers as $index => $task) {
                switch (false) {
                    case $task->isStarted():
                        yield $task->start();
                        break;

                    case $task->isTerminated():
                        yield $task->resume();
                        break;

                    default:
                        $result[$index] = $task->getReturn();
                        unset($fibers[$index]);
                }
            }
        }

        return $result;
    }

    /**
     * @param iterable<\Fiber|\Generator|callable> $tasks
     * @return array<\Fiber>
     */
    private static function toFibers(iterable $tasks): array
    {
        $fibers = [];

        foreach ($tasks as $task) {
            $fibers[] = match (true) {
                $task instanceof \Fiber => $task,
                $task instanceof \Generator => self::toFiber($task),
                default => new \Fiber($task),
            };
        }

        return $fibers;
    }

    /**
     * @param \Generator $coroutine
     * @return \Fiber
     */
    public static function toFiber(\Generator $coroutine): \Fiber
    {
        return new \Fiber(static function () use ($coroutine): mixed {
            while ($coroutine->valid()) {
                \Fiber::suspend($coroutine->current());
            }

            return $coroutine->getReturn();
        });
    }

    /**
     * @template TReturn
     *
     * @psalm-param non-empty-list<\Fiber<null, null, TReturn, null>> $tasks
     * @param iterable<\Fiber> $tasks
     * @return TReturn
     * @throws \Throwable
     */
    public static function any(iterable $tasks): mixed
    {
        $tasks = [...$tasks];

        while (true) {
            /** @var \Fiber $task */
            foreach ($tasks as $task) {
                switch (false) {
                    case $task->isStarted():
                        $task->start();
                        break;

                    case $task->isTerminated():
                        $task->resume();
                        break;

                    default:
                        return $task->getReturn();
                }
            }
        }
    }

    /**
     * @template TStart
     * @template TReturn
     *
     * @param callable(...TStart):TReturn $task
     * @param TStart ...$args
     * @return \Fiber<TStart, mixed, TReturn, mixed>
     * @throws \Throwable
     */
    public static function async(callable $task, mixed ...$args): \Fiber
    {
        $fiber = new \Fiber($task);

        if (!$fiber->isStarted()) {
            $fiber->start(...$args);
        }

        return $fiber;
    }

    /**
     * @template TStart
     * @template TReturn
     *
     * @param \Fiber<TStart, mixed, TReturn, mixed>|callable(...TStart):TReturn $task
     * @param TStart ...$args
     * @return TReturn
     * @throws \Throwable
     */
    public static function wait(\Fiber|callable $task, mixed ...$args): mixed
    {
        $task = $task instanceof \Fiber ? $task : new \Fiber($task);

        if (!$task->isStarted()) {
            $task->start(...$args);
        }

        while (!$task->isTerminated()) {
            $task->resume();
        }

        return $task->getReturn();
    }
}