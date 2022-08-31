<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Ramsey\Uuid\UuidInterface;

/**
 * @template TWindow of WindowInterface
 *
 * @template-implements FactoryInterface<TWindow>
 * @template-implements ManagerInterface<TWindow>
 * @template-implements \IteratorAggregate<UuidInterface, TWindow>
 *
 * @package ui
 */
abstract class Factory implements FactoryInterface, ManagerInterface, \IteratorAggregate
{
    /**
     * @var \WeakMap<UuidInterface, WindowInterface>
     */
    private readonly \WeakMap $map;

    public function __construct()
    {
        $this->map = new \WeakMap();
    }

    /**
     * @param string $name
     * @param positive-int $width
     * @param positive-int $height
     * @return WindowInterface
     */
    abstract protected function instance(string $name, int $width, int $height): WindowInterface;

    /**
     * {@inheritDoc}
     */
    public function create(string $name, int $width, int $height): WindowInterface
    {
        $instance = $this->instance($name, $width, $height);

        $this->map[$instance->getId()] = $instance;

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function find(UuidInterface $id): ?WindowInterface
    {
        if (isset($this->map[$id])) {
            return $this->map[$id];
        }

        foreach ($this->map as $uuid => $window) {
            if ($uuid->equals($id)) {
                return $window;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(WindowInterface $window): void
    {
        unset($this->map[$window->getId()]);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->map->count();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->map;
    }
}
