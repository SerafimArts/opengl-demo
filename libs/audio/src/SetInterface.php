<?php

declare(strict_types=1);

namespace Bic\Audio;

/**
 * @template TObject of object
 *
 * @template-extends \Traversable<array-key, TObject>
 */
interface SetInterface extends \Traversable, \Countable
{
    /**
     * {@inheritDoc}
     *
     * @psalm-return positive-int|0
     */
    public function count(): int;
}
