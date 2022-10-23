<?php

declare(strict_types=1);

namespace Bic\Loop;

/**
 * @template TReturn
 *
 * @psalm-param non-empty-list<\Fiber<null, null, TReturn, null>> $tasks
 * @param iterable<\Fiber> $tasks
 * @psalm-return non-empty-array<TReturn>
 * @throws \Throwable
 */
function all(iterable $tasks): array
{
    return Task::all($tasks);
}

/**
 * @template TReturn
 *
 * @psalm-param non-empty-list<\Fiber<null, null, TReturn, null>> $tasks
 * @param iterable<\Fiber> $tasks
 * @return TReturn
 * @throws \Throwable
 */
function any(iterable $tasks): mixed
{
    return Task::any($tasks);
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
function async(callable $task, mixed ...$args): \Fiber
{
    return Task::async($task, ...$args);
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
function wait(\Fiber|callable $task, mixed ...$args): \Fiber
{
    return Task::wait($task, ...$args);
}