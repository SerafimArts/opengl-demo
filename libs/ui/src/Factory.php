<?php

declare(strict_types=1);

namespace Bic\UI;

use Bic\UI\Window\CreateInfo;
use Bic\UI\ManagerInterface;
use Bic\UI\Window\WindowInterface;

/**
 * @template TWindow of WindowInterface
 * @template TCreateInfo of CreateInfo
 *
 * @template-implements \IteratorAggregate<array-key, TWindow>
 * @template-implements FactoryInterface<TWindow, TCreateInfo>
 * @template-implements ManagerInterface<TWindow>
 */
abstract class Factory implements FactoryInterface, ManagerInterface, \IteratorAggregate
{
    /**
     * @var \SplObjectStorage<TWindow, mixed>
     */
    private readonly \SplObjectStorage $windows;

    public function __construct()
    {
        $this->windows = new \SplObjectStorage();
    }

    /**
     * @param TCreateInfo $info
     *
     * @return TWindow
     */
    abstract protected function instance(CreateInfo $info): WindowInterface;

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        if (\Fiber::getCurrent()) {
            while ($this->windows->count() > 0) {
                foreach (self::cooperative($this->windows) as $event) {
                    if ($event !== null) {
                        \Fiber::suspend($event);
                    }
                }

                \Fiber::suspend(); // NOP
            }
        }

        while ($this->windows->count() > 0) {
            foreach (self::cooperative($this->windows) as $event);
        }
    }

    /**
     * @template TReturn of mixed
     *
     * @param \Countable&\Traversable<array-key, TReturn> $windows
     *
     * @return \Iterator<array-key, TReturn>
     * @throws \Throwable
     */
    private static function cooperative(\Traversable&\Countable $windows): \Iterator
    {
        $processes = new \WeakMap();

        while ($windows->count() > 0) {
            foreach ($windows as $runnable) {
                $process = ($processes[$runnable] ??= new \Fiber($runnable->run(...)));

                switch (false) {
                    case $process->isStarted():
                        yield $process->start();
                        break;
                    case $process->isTerminated():
                        yield $process->resume();
                        break;
                    default:
                        unset($processes[$runnable]);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function create(CreateInfo $info = new CreateInfo()): WindowInterface
    {
        $instance = $this->instance($info);

        $this->windows->attach($instance);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(WindowInterface $window): void
    {
        $this->windows->detach($window);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->windows->count();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->windows;
    }
}
