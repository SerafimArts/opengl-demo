<?php

declare(strict_types=1);

namespace Bic\Audio;

/**
 * @template TObject of object
 *
 * @template-implements SetInterface<TObject>
 * @template-implements \IteratorAggregate<array-key, TObject>
 */
abstract class Set implements SetInterface, \IteratorAggregate
{
    /**
     * @return iterable<array-key, TObject>
     */
    abstract protected function enumerate(): iterable;

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        $result = $this->enumerate();

        if ($result instanceof \Traversable) {
            return $result;
        }

        return new \ArrayIterator($result);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \iterator_count($this->getIterator());
    }
}
