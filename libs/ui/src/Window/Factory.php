<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Ramsey\Uuid\UuidInterface;

/**
 * @template TWindow of WindowInterface
 *
 * @template-implements FactoryInterface<TWindow>
 * @template-implements \IteratorAggregate<UuidInterface, TWindow>
 *
 * @package ui
 */
abstract class Factory implements FactoryInterface, \IteratorAggregate
{
    /**
     * @var \WeakMap<UuidInterface, TWindow>
     */
    private \WeakMap $windows;

    protected function __construct()
    {
        $this->windows = new \WeakMap();
    }

    /**
     * @param string $name
     * @param positive-int $width
     * @param positive-int $height
     * @return TWindow
     */
    abstract protected function instance(string $name, int $width, int $height): WindowInterface;

    /**
     * @param WindowInterface $window
     * @return void
     */
    public function detach(WindowInterface $window): void
    {
        unset($this->windows[$window->getId()]);
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MixedArgument
     */
    public function create(string $name, int $width, int $height, string $icon = null): WindowInterface
    {
        $window = $this->instance($name, $width, $height, $icon);

        return $this->windows[$window->getId()] = $window;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->windows;
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
    public function find(UuidInterface $id): ?WindowInterface
    {
        if (isset($this->windows[$id])) {
            return $this->windows[$id];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function poll(bool $blocking = false): \Iterator
    {
        while ($this->windows->count()) {
            foreach ($this->windows as $window) {
                $event = $window->poll();

                if ($blocking === false || $event !== null) {
                    yield $event;
                }
            }
        }
    }
}
