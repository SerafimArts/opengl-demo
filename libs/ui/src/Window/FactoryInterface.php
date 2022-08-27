<?php

declare(strict_types=1);

namespace Bic\UI\Window;

use Bic\UI\EventInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @template TWindow of WindowInterface
 *
 * @template-extends \Traversable<UuidInterface, TWindow>
 */
interface FactoryInterface extends \Traversable, \Countable
{
    /**
     * @param string $name
     * @param positive-int $width
     * @param positive-int $height
     * @param non-empty-string|null $icon
     * @return TWindow
     */
    public function create(string $name, int $width, int $height, string $icon = null): WindowInterface;

    /**
     * @param WindowInterface $window
     * @return void
     */
    public function detach(WindowInterface $window): void;

    /**
     * @param UuidInterface $id
     * @return TWindow|null
     */
    public function find(UuidInterface $id): ?WindowInterface;

    /**
     * @param bool $blocking
     * @return \Iterator<array-key, EventInterface|null>
     */
    public function poll(bool $blocking): \Iterator;
}
