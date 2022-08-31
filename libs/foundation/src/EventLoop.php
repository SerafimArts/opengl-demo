<?php

declare(strict_types=1);

namespace Bic\Foundation;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

final class EventLoop
{
    /**
     * @var \SplObjectStorage<\Fiber, Deferred>
     */
    private readonly \SplObjectStorage $tasks;

    /**
     * @var bool
     */
    private bool $running = false;

    public function __construct()
    {
        $this->tasks = new \SplObjectStorage();
    }

    /**
     * @param \Closure|\Generator|\Fiber $task
     * @return PromiseInterface
     */
    public function add(\Closure|\Generator|\Fiber $task): PromiseInterface
    {
        return match (true) {
            $task instanceof \Closure => $this->addFiber(new \Fiber($task)),
            $task instanceof \Generator => $this->addGenerator($task),
            $task instanceof \Fiber => $this->addFiber($task),
        };
    }

    /**
     * @param \Generator $task
     * @return PromiseInterface
     */
    private function addGenerator(\Generator $task): PromiseInterface
    {
        return $this->addFiber(new \Fiber(static function () use ($task): mixed {
            while ($task->valid()) {
                $task->send(\Fiber::suspend($task->current()));
            }

            return $task->getReturn();
        }));
    }

    /**
     * @param \Fiber $task
     * @return PromiseInterface
     */
    private function addFiber(\Fiber $task): PromiseInterface
    {
        $deferred = new Deferred();

        $this->tasks->attach($task, $deferred);

        return $deferred->promise();
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * @return void
     */
    public function stop(): void
    {
        $this->running = false;
    }

    public function run(): void
    {
        if ($this->running) {
            return;
        }

        $this->running = true;
        while($this->running) {
            foreach ($this->tasks as $task) {
                switch (false) {
                    case $task->isStarted():
                        $task->start();
                        break;

                    case $task->isTerminated():
                        $task->resume();
                        break;

                    default:

                }
            }
        }
    }
}
