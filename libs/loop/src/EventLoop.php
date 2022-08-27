<?php

declare(strict_types=1);

namespace Bic\Loop;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

final class EventLoop implements EventLoopInterface
{
    /**
     * @var \SplObjectStorage<\Generator, Deferred>
     */
    private readonly \SplObjectStorage $tasks;

    public function __construct()
    {
        $this->tasks = new \SplObjectStorage();
    }

    /**
     * {@inheritDoc}
     */
    public function attach(\Generator|callable $task): PromiseInterface
    {
        if (\is_callable($task)) {
            $task = (function () use ($task) {
                return yield $task();
            })();
        }

        return ($this->tasks[$task] = new Deferred())->promise();
    }

    /**
     * @param mixed $value
     * @param \Generator $current
     * @param Deferred $then
     * @return mixed
     */
    private function process(mixed $value, \Generator $current, Deferred $then): mixed
    {
        if ($value instanceof \Generator) {
            unset($this->tasks[$current]);

            return $this->attach($value)->then(function ($result) use ($current, $then) {
                $this->tasks[$current] = $then;

                return $result;
            });
        }

        return $value;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        while ($this->tasks->count() > 0) {
            foreach ($this->tasks as $task) {
                $deferred = $this->tasks->getInfo();

                if (!$task->valid()) {
                    unset($this->tasks[$task]);
                    $deferred->resolve($this->process($task->getReturn(), $task, $deferred));
                    continue;
                }

                $task->send($this->process($task->current(), $task, $deferred));
            }
        }
    }
}
